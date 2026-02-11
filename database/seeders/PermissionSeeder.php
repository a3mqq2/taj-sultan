<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            [
                'group' => 'products',
                'group_display_name' => 'الأصناف',
                'permissions' => [
                    ['name' => 'product.create', 'display_name' => 'إضافة صنف'],
                    ['name' => 'product.edit', 'display_name' => 'تعديل صنف'],
                    ['name' => 'product.delete', 'display_name' => 'حذف صنف'],
                ],
            ],
            [
                'group' => 'payment_methods',
                'group_display_name' => 'طرق الدفع',
                'permissions' => [
                    ['name' => 'payment_method.create', 'display_name' => 'إضافة طريقة دفع'],
                    ['name' => 'payment_method.edit', 'display_name' => 'تعديل طريقة دفع'],
                    ['name' => 'payment_method.delete', 'display_name' => 'حذف طريقة دفع'],
                ],
            ],
            [
                'group' => 'customers',
                'group_display_name' => 'الزبائن',
                'permissions' => [
                    ['name' => 'customer.create', 'display_name' => 'إضافة زبون'],
                    ['name' => 'customer.edit', 'display_name' => 'تعديل زبون'],
                    ['name' => 'customer.delete', 'display_name' => 'حذف زبون'],
                    ['name' => 'customer.add_payment', 'display_name' => 'إضافة دفعة'],
                    ['name' => 'customer.view_statement', 'display_name' => 'عرض كشف الحساب'],
                ],
            ],
            [
                'group' => 'special_orders',
                'group_display_name' => 'الطلبات الخاصة',
                'permissions' => [
                    ['name' => 'special_order.create', 'display_name' => 'إضافة طلب'],
                    ['name' => 'special_order.edit', 'display_name' => 'تعديل طلب'],
                    ['name' => 'special_order.change_status', 'display_name' => 'تغيير حالة الطلب'],
                    ['name' => 'special_order.add_payment', 'display_name' => 'إضافة دفعة'],
                    ['name' => 'special_order.delete', 'display_name' => 'حذف طلب'],
                ],
            ],
            [
                'group' => 'users',
                'group_display_name' => 'المستخدمين',
                'permissions' => [
                    ['name' => 'user.view', 'display_name' => 'عرض المستخدمين'],
                    ['name' => 'user.create', 'display_name' => 'إضافة مستخدم'],
                    ['name' => 'user.edit', 'display_name' => 'تعديل مستخدم'],
                    ['name' => 'user.delete', 'display_name' => 'حذف مستخدم'],
                ],
            ],
            [
                'group' => 'pos_points',
                'group_display_name' => 'نقاط البيع',
                'permissions' => [
                    ['name' => 'pos_point.view', 'display_name' => 'عرض نقاط البيع'],
                    ['name' => 'pos_point.edit', 'display_name' => 'تعديل نقاط البيع'],
                ],
            ],
            [
                'group' => 'reports',
                'group_display_name' => 'التقارير',
                'permissions' => [
                    ['name' => 'reports.view', 'display_name' => 'عرض التقارير'],
                ],
            ],
        ];

        $sortOrder = 0;
        foreach ($permissions as $group) {
            foreach ($group['permissions'] as $permission) {
                Permission::updateOrCreate(
                    ['name' => $permission['name']],
                    [
                        'display_name' => $permission['display_name'],
                        'group' => $group['group'],
                        'group_display_name' => $group['group_display_name'],
                        'sort_order' => $sortOrder++,
                    ]
                );
            }
        }
    }
}
