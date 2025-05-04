<?php
/**
 * Enum representing possible account statuses
 */
enum AccountStatus: string {
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case BANNED = 'banned';
}
?>