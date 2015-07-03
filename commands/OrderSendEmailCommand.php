<?php

namespace sap55\order\commands;

use jlorente\command\db\Command;

class OrderSendEmailCommand extends Command {

    public function execute() {
        $this->getReceiver()->sendEmailToUsers();
    }

}