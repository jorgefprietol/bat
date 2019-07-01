<?php

/**
 * Class login
 * handles the user's login and logout process
 */
class Login
{
    /**
     * @var object The database connection
     */
    private $db_connection = null;
    /**
     * @var array Collection of error messages
     */
    public $errors = array();
    /**
     * @var array Collection of success / neutral messages
     */
    public $messages = array();

    /**
     * the function "__construct()" automatically starts whenever an object of this class is created,
     * you know, when you do "$login = new Login();"
     */
    public function __construct()
    {
        // create/read session, absolutely necessary
        session_start();

        // check the possible login actions:
        // if user tried to log out (happen when user clicks logout button)
        if (isset($_GET["logout"])) {
            $this->doLogout();
        }
        // login via post data (if user just submitted a login form)
        elseif (isset($_POST["login"])) {
            $this->dologinWithPostData();
        }
    }

    /**
     * log in with post data
     */
    private function dologinWithPostData()
    {
        // check login form contents
        if (empty($_POST['user_name'])) {
            $this->errors[] = "Username field was empty.";
        } elseif (empty($_POST['user_password'])) {
            $this->errors[] = "Password field was empty.";
        } elseif (!empty($_POST['user_name']) && !empty($_POST['user_password'])) {

            // create a database connection, using the constants from config/db.php (which we loaded in index.php)
            $this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

            // change character set to utf8 and check it
            if (!$this->db_connection->set_charset("utf8")) {
                $this->errors[] = $this->db_connection->error;
            }

            // if no connection errors (= working database connection)
            if (!$this->db_connection->connect_errno) {

                // escape the POST stuff
                $user_name = $this->db_connection->real_escape_string($_POST['user_name']);

                // database query, getting all the info of the selected user (allows login via email address in the
                // username field)
                $sql = "SELECT codigoCliente, nombreCliente, mailCliente, codigoPromo1, codigoPromo2, codigoPromo3,  password
                        FROM clientes
                        WHERE codigoCliente = '".$user_name."';";
                $result_of_login_check = $this->db_connection->query($sql);

                // if this user exists
                if ($result_of_login_check->num_rows == 1) {

                    // get result row (as an object)
                    $result_row = $result_of_login_check->fetch_object();

                    // using PHP 5.5's password_verify() function to check if the provided password fits
                    // the hash of that user's password
                    if (password_verify($_POST['user_password'], $result_row->password)) {

                        // write user data into PHP SESSION (a file on your server)
                        $_SESSION['user_id'] = $result_row->codigoCliente;
						$_SESSION['user_name'] = $result_row->nombreCliente;
                        $_SESSION['user_email'] = $result_row->mailCliente;
                        $_SESSION['user_login_status'] = 1;
                        $_SESSION['promoactiva1'] = $result_row->codigoPromo1;
                        $_SESSION['promoactiva2'] = $result_row->codigoPromo2;
                        $_SESSION['promoactiva3'] = $result_row->codigoPromo3;
                        $sqlpromoactiva=mysqli_query($this->db_connection, "select min(numeroUnidBase) as numeroUnidBase, codigoPromocion, max(porcentaje) as porcentaje from promociones where codigoPromocion in (".$_SESSION['promoactiva1'].",".$_SESSION['promoactiva2'].",".$_SESSION['promoactiva3'].") and numeroUnidBase<>0");
                        $rowpromoactiva=mysqli_fetch_array($sqlpromoactiva);

                        $_SESSION['promoactiva'] = $rowpromoactiva['codigoPromocion'];
                        $_SESSION['numeroUnidBase'] = $rowpromoactiva['numeroUnidBase'];
                        $_SESSION['porcentaje'] = $rowpromoactiva['porcentaje'];
/*                         print("promoactiva:".$_SESSION['promoactiva']."numeroUnidBase:".$_SESSION['numeroUnidBase']."porcentaje:".$_SESSION['porcentaje']);
                        die(); */
                    } else {
                        $this->errors[] = "Contraseña no coincide.";
                    }
                } else {
                    $this->errors[] = "Usuario no coincide.";
                }
            } else {
                $this->errors[] = "Problema de conexión de base de datos.";
            }
        }
    }

    /**
     * perform the logout
     */
    public function doLogout()
    {
        // delete the session of the user
        $_SESSION = array();
        session_destroy();
        // return a little feeedback message
        $this->messages[] = "Has sido desconectado.";

    }

    /**
     * simply return the current state of the user's login
     * @return boolean user's login status
     */
    public function isUserLoggedIn()
    {
        if (isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] == 1) {
            return true;
        }
        // default return
        return false;
    }
}
