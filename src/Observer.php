<?php
interface Observer {
    public function update(string $eventMessage): void;
}
?>