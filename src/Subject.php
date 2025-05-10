<?php
abstract class Subject {
    public function attach($user, $subscriptionName) {
        try {
            $dbCnx = require('db.php');
            $stmt = $dbCnx->prepare("SELECT * FROM user_subscriptions WHERE userId = :userId");
            $stmt->bindParam(':userId', $user->getId());
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                $stmt = $dbCnx->prepare("UPDATE user_subscriptions SET (?) = 1 WHERE userId = (?)");
                $stmt->execute([$subscriptionName, $user->getId()]);
                return;
            }
            $stmt = $dbCnx->prepare("INSERT INTO user_subscriptions (userId,?) VALUES (?,1)");
            $stmt->execute([$subscriptionName,$user->getId()]);
        }
        catch (Exception $e) {
            throw new Exception("Error updating subscription: " . $e->getMessage());
        }

    }

    public function detach($user, $subscriptionName) {
        $dbCnx = require('db.php');
        try {
            $stmt = $dbCnx->prepare("UPDATE user_subscriptions set (?) = 0 WHERE userId = (?)");
            $stmt->execute($subscriptionName, $user->getId());
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        catch (Exception $e) {
            throw new Exception("Error updating subscription: " . $e->getMessage());
        }


}

    public function notify($message,$subscriptionName) {
        $dbCnx = require('db.php');
        $stmt = $dbCnx->prepare("SELECT * FROM user_subscriptions WHERE $subscriptionName = 1");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row) {
            $username = $row['username'];
            $user = new Alumni($username);
            $user->update($message);
        }
    }
}
?>