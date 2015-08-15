<?php

/**
 * Configure App specific behavior for
 * Maestrano SSO
 */
class MnoSsoUser extends Maestrano_Sso_User
{
  /**
   * Database connection
   * @var PDO
   */
  public $connection = null;


  /**
   * Extend constructor to inialize app specific objects
   *
   * @param OneLogin_Saml_Response $saml_response
   *   A SamlResponse object from Maestrano containing details
   *   about the user being authenticated
   */
  public function __construct($saml_response, $opts = array())
  {
    // Call Parent
    parent::__construct($saml_response);

    // Assign new attributes
    $this->connection = $opts['db_connection'];
  }

  /**
  * Find or Create a user based on the SAML response parameter and Add the user to current session
  */
  public function findOrCreate() {
    // Find user by uid or email
    $local_id = $this->getLocalIdByUid();
    if($local_id == null) { $local_id = $this->getLocalIdByEmail(); }

    if ($local_id) {
      // User found, load it
      $this->local_id = $local_id;
      $this->syncLocalDetails();
    } else {
      // New user, create it
      $this->local_id = $this->createLocalUser();
      $this->setLocalUid();
    }

    // Add user to current session
    $this->setInSession();
  }

  /**
   * Sign the user in the application.
   * Parent method deals with putting the mno_uid,
   * mno_session and mno_session_recheck in session.
   *
   * @return boolean whether the user was successfully set in session or not
   */
  protected function setInSession()
  {
    if ($this->local_id) {

  			global $current_user;
  			get_currentuserinfo();

        wp_set_current_user($this->local_id);
        wp_set_auth_cookie($this->local_id, true);
        do_action('wp_login', $this->local_id);

        return true;
    } else {
        return false;
    }
  }


  /**
   * Used by createLocalUserOrDenyAccess to create a local user
   * based on the sso user.
   * If the method returns null then access is denied
   *
   * @return the ID of the user created, null otherwise
   */
  protected function createLocalUser()
  {
    $lid = null;

		$user = $this->buildLocalUser();

    // Create user
    $lid = wp_insert_user($user);

    if (!is_int($lid) || $lid == 0) {
      $lid = null;
    }

    return $lid;
  }

  /**
   * Build a local user for creation
   *
   * @return a hash of user attributes
   */
  protected function buildLocalUser()
  {
    $fullname = ($this->getFirstName() . ' ' . $this->getLastName());
    $password = $this->generatePassword();

    $user = Array(
      'user_login'    => $this->uid,
      'user_email'    => $this->getEmail(),
      'role'          => $this->getRoleToAssign(),
      'first_name'    => $this->getFirstName(),
      'last_name'     => $this->getLastName(),
      'nickname'      => $fullname,
      'display_name'  => $fullname,
      'user_nicename' => $fullname,
      'user_pass'     => $password
    );

    return $user;
  }

  /**
   * Return the role to give to the user based on context
   * If the user is the owner of the app or at least Admin
   * for each organization, then it is given the role of 'Admin'.
   * Return 'User' role otherwise
   *
   * @return the ID of the user created, null otherwise
   */
  public function getRoleToAssign() {
    if ($this->getGroupRole() == 'Admin' || $this->getGroupRole() == 'Super Admin') {
      $role = 'administrator';
    } else {
      $role = 'contributor'; // User
    }

    return $role;
  }

  /**
   * Get the ID of a local user via Maestrano UID lookup
   *
   * @return a user ID if found, null otherwise
   */
  protected function getLocalIdByUid()
  {
    global $wpdb;

    $result = $wpdb->get_row( $wpdb->prepare("SELECT ID FROM $wpdb->users WHERE mno_uid = %s", $this->uid) );

    if ($result && $result->ID) {
      return $result->ID;
    }

    return null;
  }

  /**
   * Get the ID of a local user via email lookup
   *
   * @return a user ID if found, null otherwise
   */
  protected function getLocalIdByEmail()
  {
    $result = get_user_by('email',$this->email);

    if ($result && $result->id) {
      return $result->id;
    }

    return null;
  }

  /**
   * Set all 'soft' details on the user (like name, surname, email)
   * Implementing this method is optional.
   *
   * @return boolean whether the user was synced or not
   */
   protected function syncLocalDetails()
   {
     if($this->local_id) {
       $fullname = $this->getFirstName() . ' ' . $this->getLastName();

       // Update user table
       $upd = $this->connection->update($this->connection->users,
         array(
           'user_login' => $this->uid,
           'user_nicename' => $fullname,
           'display_name'  => $fullname,
         ),
         array('ID' => $this->local_id)
       );

       // Update user meta
       $upd = $this->connection->update($this->connection->usermeta,
         array('meta_value' => $this->name),
         array('user_id' => $this->local_id, 'meta_key' => 'first_name')
       );
       $upd = $this->connection->update($this->connection->usermeta,
         array('meta_value' => $this->surname),
         array('user_id' => $this->local_id, 'meta_key' => 'last_name')
       );

       return $upd;
     }

     return false;
   }

  /**
   * Set the Maestrano UID on a local user via id lookup
   *
   * @return a user ID if found, null otherwise
   */
  protected function setLocalUid()
  {
    if($this->local_id) {
      $upd = $this->connection->update($this->connection->users,
        array(
          'mno_uid' => $this->uid,
        ),
        array('ID' => $this->local_id)
      );

      return $upd;
    }

    return false;
  }

  /**
   * Generate a random password.
   * Convenient to set dummy passwords on users
   *
   * @return string a random password
   */
  protected function generatePassword()
  {
    $length = 20;
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
  }
}
