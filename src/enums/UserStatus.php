<?php
enum UserStatus: string {
    case Active = 'active';
    case Inactive = 'inactive';
    case Banned = 'banned';
}