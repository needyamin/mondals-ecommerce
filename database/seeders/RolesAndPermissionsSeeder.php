<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ─── Define Permissions ──────────────────────────────────────

        $permissions = [
            // User Management
            'users.view', 'users.create', 'users.update', 'users.delete',
            'users.ban', 'users.assign-roles',

            // Vendor Management
            'vendors.view', 'vendors.create', 'vendors.update', 'vendors.delete',
            'vendors.approve', 'vendors.reject', 'vendors.suspend',

            // Product Management
            'products.view', 'products.create', 'products.update', 'products.delete',
            'products.approve', 'products.reject', 'products.feature',

            // Category Management
            'categories.view', 'categories.create', 'categories.update', 'categories.delete',

            // Brand Management
            'brands.view', 'brands.create', 'brands.update', 'brands.delete',

            // Attribute Management
            'attributes.view', 'attributes.create', 'attributes.update', 'attributes.delete',

            // Order Management
            'orders.view', 'orders.update', 'orders.delete', 'orders.cancel',
            'orders.refund', 'orders.view-all',

            // Coupon Management
            'coupons.view', 'coupons.create', 'coupons.update', 'coupons.delete',

            // Review Management
            'reviews.view', 'reviews.approve', 'reviews.reject', 'reviews.delete',

            // CMS Management
            'pages.view', 'pages.create', 'pages.update', 'pages.delete',
            'banners.view', 'banners.create', 'banners.update', 'banners.delete',
            'menus.view', 'menus.create', 'menus.update', 'menus.delete',
            'blog.view', 'blog.create', 'blog.update', 'blog.delete',

            // Settings & Admin
            'settings.view', 'settings.update',
            'themes.view', 'themes.manage',
            'plugins.view', 'plugins.manage',
            'audit-logs.view',
            'reports.view',

            // Shipping & Tax
            'shipping.view', 'shipping.manage',
            'tax.view', 'tax.manage',

            // Payment
            'payment.view', 'payment.manage',

            // Vendor Payouts
            'payouts.view', 'payouts.manage',

            // Notifications
            'notifications.view', 'notifications.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // ─── Create Roles ────────────────────────────────────────────

        // ADMIN: Full access to everything
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->syncPermissions(Permission::all());

        // STAFF: Admin-like but restricted from critical settings
        $staffRole = Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'web']);
        $staffRole->syncPermissions([
            'users.view', 'users.update',
            'vendors.view', 'vendors.approve', 'vendors.reject',
            'products.view', 'products.update', 'products.approve', 'products.reject', 'products.feature',
            'categories.view', 'categories.create', 'categories.update',
            'brands.view', 'brands.create', 'brands.update',
            'attributes.view',
            'orders.view', 'orders.update', 'orders.view-all',
            'coupons.view', 'coupons.create', 'coupons.update',
            'reviews.view', 'reviews.approve', 'reviews.reject',
            'pages.view', 'pages.create', 'pages.update',
            'banners.view', 'banners.create', 'banners.update',
            'menus.view',
            'blog.view', 'blog.create', 'blog.update',
            'reports.view',
            'shipping.view',
            'tax.view',
            'payouts.view',
            'notifications.view',
        ]);

        // VENDOR: Manage own store, products, orders
        $vendorRole = Role::firstOrCreate(['name' => 'vendor', 'guard_name' => 'web']);
        $vendorRole->syncPermissions([
            'products.view', 'products.create', 'products.update', 'products.delete',
            'orders.view',
            'coupons.view', 'coupons.create', 'coupons.update', 'coupons.delete',
            'reviews.view',
            'reports.view',
            'payouts.view',
        ]);

        // CUSTOMER: Basic account management (permissions enforced via policies mostly)
        $customerRole = Role::firstOrCreate(['name' => 'customer', 'guard_name' => 'web']);
        $customerRole->syncPermissions([
            'orders.view',
            'reviews.view',
        ]);

        // ─── Create Default Admin User ───────────────────────────────

        $admin = User::firstOrCreate(
            ['email' => 'admin@mondals.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
                'status' => 'active',
            ]
        );
        $admin->assignRole('admin');

        $this->command->info('✅ Roles and permissions seeded successfully!');
        $this->command->info("   Admin: admin@mondals.com / password");
    }
}
