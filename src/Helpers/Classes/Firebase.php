<?php
namespace Artincms\LHS\Helpers\Classes;


class Firebase {

   private $FIREBASE_API_KEY = "AAAAxfQZLqY:APA91bELfW3GfgCXcT6vU9eh98e8pg1XOy3k4ODR6p7gk7xPiQzG9cf2ORnQ4IpovHs6goPNeX1J2Xk7gTjkZFC1zjYtlE7oHVb1nn5ox4fdrgyb--uMfPJYMlqcHpxxt9lORdLFNBK3";
    // sending push message to single user by firebase reg id
    public function sendData($to, $message) {
        $fields = array(
            'to' => $to,
            'data' => $message
        );
        return $this->sendPushNotification($fields);
    }


    public function sendDataAndNotifi($to, $message,$nodifi) {
        $fields = array(
            'to' => $to,
            'data' => $message,
            'notification' => $nodifi,
        );
        return $this->sendPushNotification($fields);
    }

    // Sending message to a topic by topic name
    public function sendDataToTopic($to, $message) {
        $fields = array(
            'to' => '/topics/' . $to,
            'data' => $message,
        );
        return $this->sendPushNotification($fields);
    }

    // sending push message to multiple users by firebase registration ids
    public function sendMultiple($registration_ids, $message) {
        $fields = array(
            'to' => $registration_ids,
            'data' => $message,
        );

        return $this->sendPushNotification($fields);
    }

    // function makes curl request to firebase servers
    private function sendPushNotification($fields) {
        


        // Set POST variables
        $url = 'https://fcm.googleapis.com/fcm/send';

        $headers = array(
            'Authorization: key=' .$this->FIREBASE_API_KEY ,
            'Content-Type: application/json'
        );
        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);


        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }

        // Close connection
        curl_close($ch);

        return $result;
    }
}
