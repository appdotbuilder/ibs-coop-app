<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Member;
use App\Models\Product;
use App\Models\FinancialAccount;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CooperativeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default users with different roles
        $officer = User::create([
            'name' => 'Ahmad Syukur',
            'email' => 'officer@ibs.coop',
            'password' => Hash::make('password'),
            'role' => 'officer',
            'is_active' => true,
        ]);

        $pengelola = User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'pengelola@ibs.coop',
            'password' => Hash::make('password'),
            'role' => 'pengelola',
            'is_active' => true,
        ]);

        $pelanggan = User::create([
            'name' => 'Budi Setiawan',
            'email' => 'pelanggan@ibs.coop',
            'password' => Hash::make('password'),
            'role' => 'pelanggan',
            'is_active' => true,
        ]);

        // Create sample members
        $memberData = [
            [
                'name' => 'Budi Setiawan',
                'email' => 'budi@example.com',
                'phone' => '081234567890',
                'address' => 'Jl. Merdeka No. 123, Jakarta',
                'birth_date' => '1980-05-15',
                'gender' => 'male',
                'id_card_number' => '3171051505800001',
                'join_date' => '2023-01-15',
                'share_capital' => 1000000,
                'mandatory_savings' => 500000,
                'voluntary_savings' => 250000,
                'points' => 150,
            ],
            [
                'name' => 'Sari Dewi',
                'email' => 'sari@example.com',
                'phone' => '081234567891',
                'address' => 'Jl. Sudirman No. 456, Jakarta',
                'birth_date' => '1985-08-22',
                'gender' => 'female',
                'id_card_number' => '3171052208850002',
                'join_date' => '2023-02-10',
                'share_capital' => 1500000,
                'mandatory_savings' => 750000,
                'voluntary_savings' => 300000,
                'points' => 200,
            ],
            [
                'name' => 'Joko Widodo',
                'email' => 'joko@example.com',
                'phone' => '081234567892',
                'address' => 'Jl. Thamrin No. 789, Jakarta',
                'birth_date' => '1975-12-03',
                'gender' => 'male',
                'id_card_number' => '3171050312750003',
                'join_date' => '2023-03-05',
                'share_capital' => 2000000,
                'mandatory_savings' => 1000000,
                'voluntary_savings' => 500000,
                'points' => 350,
            ],
            [
                'name' => 'Rina Susanti',
                'email' => 'rina@example.com',
                'phone' => '081234567893',
                'address' => 'Jl. Gatot Subroto No. 321, Jakarta',
                'birth_date' => '1990-04-18',
                'gender' => 'female',
                'id_card_number' => '3171051804900004',
                'join_date' => '2023-04-20',
                'share_capital' => 750000,
                'mandatory_savings' => 375000,
                'voluntary_savings' => 150000,
                'points' => 75,
            ],
            [
                'name' => 'Agus Salim',
                'email' => 'agus@example.com',
                'phone' => '081234567894',
                'address' => 'Jl. HR Rasuna Said No. 654, Jakarta',
                'birth_date' => '1982-09-10',
                'gender' => 'male',
                'id_card_number' => '3171051009820005',
                'join_date' => '2023-05-12',
                'share_capital' => 1200000,
                'mandatory_savings' => 600000,
                'voluntary_savings' => 200000,
                'points' => 120,
            ],
        ];

        foreach ($memberData as $index => $data) {
            $data['member_id'] = 'IBS' . str_pad((string) ($index + 1), 6, '0', STR_PAD_LEFT);
            Member::create($data);
        }

        // Create sample products
        $productData = [
            // Food & Beverages
            [
                'sku' => 'FB001',
                'name' => 'Beras Premium 5kg',
                'description' => 'Beras premium kualitas terbaik untuk keluarga',
                'category' => 'Food & Beverages',
                'purchase_price' => 45000,
                'selling_price' => 55000,
                'member_price' => 52000,
                'stock_quantity' => 100,
                'minimum_stock' => 20,
                'unit' => 'bag',
                'allow_installment' => true,
                'points_earned' => 5,
            ],
            [
                'sku' => 'FB002',
                'name' => 'Minyak Goreng 2L',
                'description' => 'Minyak goreng kemasan 2 liter',
                'category' => 'Food & Beverages',
                'purchase_price' => 25000,
                'selling_price' => 30000,
                'member_price' => 28000,
                'stock_quantity' => 80,
                'minimum_stock' => 15,
                'unit' => 'bottle',
                'allow_installment' => false,
                'points_earned' => 3,
            ],
            [
                'sku' => 'FB003',
                'name' => 'Gula Pasir 1kg',
                'description' => 'Gula pasir kristal putih kemasan 1kg',
                'category' => 'Food & Beverages',
                'purchase_price' => 12000,
                'selling_price' => 15000,
                'member_price' => 14000,
                'stock_quantity' => 150,
                'minimum_stock' => 30,
                'unit' => 'pack',
                'allow_installment' => false,
                'points_earned' => 1,
            ],
            [
                'sku' => 'FB004',
                'name' => 'Telur Ayam 1kg',
                'description' => 'Telur ayam segar kemasan 1kg',
                'category' => 'Food & Beverages',
                'purchase_price' => 20000,
                'selling_price' => 25000,
                'member_price' => 23000,
                'stock_quantity' => 60,
                'minimum_stock' => 10,
                'unit' => 'pack',
                'allow_installment' => false,
                'points_earned' => 2,
            ],
            [
                'sku' => 'FB005',
                'name' => 'Susu UHT 1L',
                'description' => 'Susu UHT full cream kemasan 1 liter',
                'category' => 'Food & Beverages',
                'purchase_price' => 15000,
                'selling_price' => 18000,
                'member_price' => 17000,
                'stock_quantity' => 90,
                'minimum_stock' => 20,
                'unit' => 'pack',
                'allow_installment' => false,
                'points_earned' => 2,
            ],

            // Household Items
            [
                'sku' => 'HH001',
                'name' => 'Sabun Cuci Piring 800ml',
                'description' => 'Sabun pencuci piring cair kemasan 800ml',
                'category' => 'Household',
                'purchase_price' => 8000,
                'selling_price' => 10000,
                'member_price' => 9500,
                'stock_quantity' => 120,
                'minimum_stock' => 25,
                'unit' => 'bottle',
                'allow_installment' => false,
                'points_earned' => 1,
            ],
            [
                'sku' => 'HH002',
                'name' => 'Deterjen Bubuk 1kg',
                'description' => 'Deterjen bubuk untuk mencuci pakaian',
                'category' => 'Household',
                'purchase_price' => 18000,
                'selling_price' => 22000,
                'member_price' => 20000,
                'stock_quantity' => 75,
                'minimum_stock' => 15,
                'unit' => 'pack',
                'allow_installment' => false,
                'points_earned' => 2,
            ],
            [
                'sku' => 'HH003',
                'name' => 'Tissue Toilet 12 Roll',
                'description' => 'Tissue toilet premium pack 12 roll',
                'category' => 'Household',
                'purchase_price' => 35000,
                'selling_price' => 42000,
                'member_price' => 40000,
                'stock_quantity' => 50,
                'minimum_stock' => 10,
                'unit' => 'pack',
                'allow_installment' => false,
                'points_earned' => 4,
            ],

            // Electronics
            [
                'sku' => 'EL001',
                'name' => 'Rice Cooker 1.8L',
                'description' => 'Rice cooker kapasitas 1.8 liter dengan anti lengket',
                'category' => 'Electronics',
                'purchase_price' => 250000,
                'selling_price' => 320000,
                'member_price' => 300000,
                'stock_quantity' => 25,
                'minimum_stock' => 5,
                'unit' => 'unit',
                'allow_installment' => true,
                'points_earned' => 30,
            ],
            [
                'sku' => 'EL002',
                'name' => 'Blender 2in1',
                'description' => 'Blender 2 in 1 dengan dry mill untuk bumbu',
                'category' => 'Electronics',
                'purchase_price' => 180000,
                'selling_price' => 230000,
                'member_price' => 210000,
                'stock_quantity' => 20,
                'minimum_stock' => 3,
                'unit' => 'unit',
                'allow_installment' => true,
                'points_earned' => 23,
            ],

            // Health & Beauty
            [
                'sku' => 'HB001',
                'name' => 'Vitamin C 1000mg',
                'description' => 'Vitamin C 1000mg tablet effervescent isi 10',
                'category' => 'Health & Beauty',
                'purchase_price' => 25000,
                'selling_price' => 32000,
                'member_price' => 30000,
                'stock_quantity' => 40,
                'minimum_stock' => 8,
                'unit' => 'box',
                'allow_installment' => false,
                'points_earned' => 3,
            ],
            [
                'sku' => 'HB002',
                'name' => 'Masker Wajah 3ply',
                'description' => 'Masker wajah 3 ply disposable isi 50 pcs',
                'category' => 'Health & Beauty',
                'purchase_price' => 45000,
                'selling_price' => 55000,
                'member_price' => 52000,
                'stock_quantity' => 100,
                'minimum_stock' => 20,
                'unit' => 'box',
                'allow_installment' => false,
                'points_earned' => 5,
            ],
        ];

        foreach ($productData as $data) {
            Product::create($data);
        }

        // Create basic financial accounts for Indonesian cooperative
        $accountData = [
            // Assets
            ['account_code' => '1001', 'account_name' => 'Kas', 'account_type' => 'asset', 'category' => 'cash', 'balance' => 10000000],
            ['account_code' => '1002', 'account_name' => 'Bank BRI', 'account_type' => 'asset', 'category' => 'bank', 'balance' => 25000000],
            ['account_code' => '1003', 'account_name' => 'Bank Mandiri', 'account_type' => 'asset', 'category' => 'bank', 'balance' => 15000000],
            ['account_code' => '1101', 'account_name' => 'Piutang Anggota', 'account_type' => 'asset', 'category' => 'receivable', 'balance' => 5000000],
            ['account_code' => '1201', 'account_name' => 'Persediaan Barang Dagangan', 'account_type' => 'asset', 'category' => 'inventory', 'balance' => 20000000],
            ['account_code' => '1301', 'account_name' => 'Peralatan Toko', 'account_type' => 'asset', 'category' => 'other', 'balance' => 8000000],

            // Liabilities
            ['account_code' => '2001', 'account_name' => 'Utang Dagang', 'account_type' => 'liability', 'category' => 'payable', 'balance' => 3000000],
            ['account_code' => '2101', 'account_name' => 'Simpanan Wajib Anggota', 'account_type' => 'liability', 'category' => 'other', 'balance' => 15000000],
            ['account_code' => '2102', 'account_name' => 'Simpanan Sukarela Anggota', 'account_type' => 'liability', 'category' => 'other', 'balance' => 8000000],

            // Equity
            ['account_code' => '3001', 'account_name' => 'Simpanan Pokok', 'account_type' => 'equity', 'category' => 'capital', 'balance' => 30000000],
            ['account_code' => '3002', 'account_name' => 'Cadangan Umum', 'account_type' => 'equity', 'category' => 'capital', 'balance' => 5000000],
            ['account_code' => '3003', 'account_name' => 'SHU Tahun Berjalan', 'account_type' => 'equity', 'category' => 'capital', 'balance' => 12000000],

            // Income
            ['account_code' => '4001', 'account_name' => 'Penjualan', 'account_type' => 'income', 'category' => 'sales', 'balance' => 0],
            ['account_code' => '4002', 'account_name' => 'Pendapatan Bunga Simpanan', 'account_type' => 'income', 'category' => 'other', 'balance' => 0],

            // Expenses
            ['account_code' => '5001', 'account_name' => 'Harga Pokok Penjualan', 'account_type' => 'expense', 'category' => 'cost_of_goods', 'balance' => 0],
            ['account_code' => '6001', 'account_name' => 'Beban Operasional', 'account_type' => 'expense', 'category' => 'operating_expense', 'balance' => 0],
            ['account_code' => '6002', 'account_name' => 'Beban Gaji', 'account_type' => 'expense', 'category' => 'operating_expense', 'balance' => 0],
            ['account_code' => '6003', 'account_name' => 'Beban Listrik', 'account_type' => 'expense', 'category' => 'operating_expense', 'balance' => 0],
            ['account_code' => '6004', 'account_name' => 'Beban Sewa', 'account_type' => 'expense', 'category' => 'operating_expense', 'balance' => 0],
        ];

        foreach ($accountData as $data) {
            FinancialAccount::create($data);
        }
    }
}