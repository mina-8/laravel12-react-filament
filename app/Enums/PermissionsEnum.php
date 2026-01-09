<?php

namespace App\Enums;

enum PermissionsEnum
{
    public static function SuperAdminPermissions() : array
    {
        return [
            "view_admin","view_any_admin","create_admin","update_admin","restore_admin","restore_any_admin","replicate_admin","reorder_admin","delete_admin","delete_any_admin","force_delete_admin","force_delete_any_admin",
            "view_role","view_any_role","create_role","update_role","restore_role","restore_any_role","replicate_role","reorder_role","delete_role","delete_any_role","force_delete_role","force_delete_any_role",
            "view_permission","view_any_permission","create_permission","update_permission","restore_permission","restore_any_permission","replicate_permission","reorder_permission","delete_permission","delete_any_permission","force_delete_permission","force_delete_any_permission",
            "view_user","view_any_user","create_user","update_user","restore_user","restore_any_user","replicate_user","reorder_user","delete_user","delete_any_user","force_delete_user","force_delete_any_user",
            "view_contact::us" , "view_any_contact::us" , "create_contact::us" , "update_contact::us" , "restore_contact::us" , "restore_any_contact::us" , "replicate_contact::us" , "reorder_contact::us" , "delete_contact::us" , "delete_any_contact::us" , "force_delete_contact::us" , "force_delete_any_contact::us" ,
        ];
    }

    public static function CrmPermissions(): array
    {
        return [
            "view_contact::us","view_any_contact::us","create_contact::us","update_contact::us","restore_contact::us","restore_any_contact::us","replicate_contact::us","reorder_contact::us","delete_contact::us","delete_any_contact::us","force_delete_contact::us","force_delete_any_contact::us",
            // "view_order","view_any_order","create_order","update_order","restore_order","restore_any_order","replicate_order","reorder_order","delete_order","delete_any_order","force_delete_order","force_delete_any_order"
        ];
    }
}
