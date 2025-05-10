<?php
abstract class Subject {
    public function attach($user, $subscriptionName) {
        try {
            $subscriptionMap = [
                'Newsletter' => 'subscribed_newsletter',
                'Mentorship' => 'subscribed_mentorship',
                'Event' => 'subscribed_events'
            ];
            
            $calledClass = get_called_class();
            
            if (!isset($subscriptionMap[$calledClass])) {
                throw new Exception("No subscription mapping for class: $calledClass");
            }
            
            $subscriptionName = $subscriptionMap[$calledClass];
            $dbCnx = require('db.php');
            $stmt = $dbCnx->prepare("SELECT * FROM user_subscriptions WHERE user_id = ?");
            $stmt->execute([$user->getId()]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result) 
            {
                $stmt = $dbCnx->prepare("UPDATE user_subscriptions SET $subscriptionName = 1 WHERE user_id = ?");
                $stmt->execute([$user->getId()]);
                return;
            }
            else
            {
                $stmt = $dbCnx->prepare("INSERT INTO user_subscriptions (user_id, ?) VALUES (?, 1)");
                $stmt->execute([$subscriptionName,$user->getId()]);
            }
        }
        catch (Exception $e) 
        {
            throw new Exception("Error updating subscription: " . $e->getMessage());
        }

    }

    public function detach($user, $subscriptionName) {
        try {
            $subscriptionMap = [
                'Newsletter' => 'subscribed_newsletter',
                'Mentorship' => 'subscribed_mentorship',
                'Event' => 'subscribed_events'
            ];
            
            $calledClass = get_called_class();
            
            if (!isset($subscriptionMap[$calledClass])) {
                throw new Exception("No subscription mapping for class: $calledClass");
            }
            
            $subscriptionName = $subscriptionMap[$calledClass];
            $dbCnx = require('db.php');
            $stmt = $dbCnx->prepare("SELECT * FROM user_subscriptions WHERE user_id = ?");
            $stmt->execute([$user->getId()]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result) 
            {
                $stmt = $dbCnx->prepare("UPDATE user_subscriptions SET $subscriptionName = 0 WHERE user_id = ?");
                $stmt->execute([$user->getId()]);
                return;
            }
            else
            {
                $stmt = $dbCnx->prepare("INSERT INTO user_subscriptions (user_id, ?) VALUES (?, 0)");
                $stmt->execute([$subscriptionName,$user->getId()]);
            }
        }
        catch (Exception $e) 
        {
            throw new Exception("Error updating subscription: " . $e->getMessage() );
        }



}

    public function notify($message) {
        $subscriptionMap = [
            'Newsletter' => 'subscribed_newsletter',
            'Mentorship' => 'subscribed_mentorship',
            'Event' => 'subscribed_events'
        ];
        
        $calledClass = get_called_class();
        
        if (!isset($subscriptionMap[$calledClass])) {
            throw new Exception("No subscription mapping for class: $calledClass");
        }
        
        $subscriptionName = $subscriptionMap[$calledClass];
        $dbCnx = require('db.php');

        try {
            // Get all subscribed users
            $stmt = $dbCnx->prepare("
                SELECT user_id 
                FROM user_subscriptions 
                WHERE $subscriptionName = 1
            ");
            $stmt->execute();
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $user = new Alumni(User::getUsernameFromId($row['user_id']));
                $user->update($message);
            }
            
        } catch (PDOException $e) {
            error_log("Notification failed: " . $e->getMessage());
            throw new Exception("Failed to send notifications");
        }
    }
    public function isSubscribed($user) {
        $calledClass = get_called_class();
        
        $subscriptionMap = [
            'Newsletter' => 'subscribed_newsletter',
            'Mentorship' => 'subscribed_mentorship',
            'Event' => 'subscribed_events'
        ];
        
        if (!isset($subscriptionMap[$calledClass])) {
            throw new Exception("No subscription mapping for class: $calledClass");
        }
        
        $subscriptionName = $subscriptionMap[$calledClass];
        $dbCnx = require('db.php');
        $stmt = $dbCnx->prepare("SELECT $subscriptionName FROM user_subscriptions WHERE user_id = ?");
        $stmt->execute([$user->getId()]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result && $result[$subscriptionName] == 1;
    }
}
?>