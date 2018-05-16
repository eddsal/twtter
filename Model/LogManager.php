<?php
namespace LogManager;
// LOG MANEGER IS FOR THE SECURITY(EVERY USERNAME OR ID HAVE A SECURITY LOG MANGER)
use Cool\DBManager;

class LogManager
{
        ///IS FOR TRACKING THE USER MOVMENT(login logout upload rename......)
    private function userTracker()
    {
        if (!empty($_SESSION['screenName'])) {
            $begin = 'User ' . $_SESSION['screenName'] . '(' . $_SESSION['id'] . ')';
        } else {
            $begin = 'Unknown user';
        }
        return $begin . ' ' . $_GET['action'] . ' at ' . date('r') . ': ';
    }
   // is for write to the security or access log what user did
    public function writeToLog($newMessage, $security = true)
    {
        if ($security === false) {
            $file = fopen('securitylogs/access.log', 'ab');
        } else {
            $file = fopen('securitylogs/security.log', 'ab');
        }
        $newMessage = $this->userTracker().$newMessage;
        fwrite($file, $newMessage . "\n");
        fclose($file);
    }

}