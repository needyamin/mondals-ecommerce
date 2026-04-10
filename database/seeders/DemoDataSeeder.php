<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\User;
use App\Notifications\NewOrderAdminNotification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('notifications')->truncate();
        DB::table('model_has_roles')->truncate();
        DB::table('model_has_permissions')->truncate();
        DB::table('users')->truncate();
        DB::table('vendors')->truncate();
        DB::table('brands')->truncate();
        DB::table('categories')->truncate();
        DB::table('attributes')->truncate();
        DB::table('attribute_values')->truncate();
        DB::table('products')->truncate();
        DB::table('product_images')->truncate();
        DB::table('product_variants')->truncate();
        DB::table('product_variant_values')->truncate();
        DB::table('category_product')->truncate();
        DB::table('attribute_product')->truncate();
        DB::table('addresses')->truncate();
        DB::table('coupons')->truncate();
        DB::table('tax_rates')->truncate();
        DB::table('shipping_zones')->truncate();
        DB::table('shipping_zone_regions')->truncate();
        DB::table('shipping_methods')->truncate();
        DB::table('orders')->truncate();
        DB::table('order_items')->truncate();
        DB::table('order_status_history')->truncate();
        DB::table('vendor_earnings')->truncate();
        DB::table('vendor_payouts')->truncate();
        DB::table('reviews')->truncate();
        DB::table('wishlists')->truncate();
        DB::table('pages')->truncate();
        DB::table('banners')->truncate();
        DB::table('menus')->truncate();
        DB::table('menu_items')->truncate();
        DB::table('blog_categories')->truncate();
        DB::table('blog_posts')->truncate();
        DB::table('blog_category_post')->truncate();
        DB::table('settings')->truncate();
        DB::table('currencies')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // ── Demo Users ──
        $admin = User::updateOrCreate(['email' => 'admin@mondals.com'], [
            'name' => 'Super Admin',
            'password' => bcrypt('password'),
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('admin');

        $vendor1 = User::updateOrCreate(['email'=>'vendor1@mondals.com'],['name'=>'Mondal Electronics','password'=>bcrypt('password'),'phone'=>'+8801711111111','status'=>'active','email_verified_at'=>now()]);
        $vendor1->assignRole('vendor');
        $vendor2 = User::updateOrCreate(['email'=>'vendor2@mondals.com'],['name'=>'Fashion Hub BD','password'=>bcrypt('password'),'phone'=>'+8801722222222','status'=>'active','email_verified_at'=>now()]);
        $vendor2->assignRole('vendor');
        $vendor3 = User::updateOrCreate(['email'=>'vendor3@mondals.com'],['name'=>'Digital World','password'=>bcrypt('password'),'phone'=>'+8801733333333','status'=>'active','email_verified_at'=>now()]);
        $vendor3->assignRole('vendor');

        $staff = User::updateOrCreate(['email'=>'staff@mondals.com'],['name'=>'Staff Member','password'=>bcrypt('password'),'status'=>'active','email_verified_at'=>now()]);
        $staff->assignRole('staff');

        $customers = [];
        foreach (['Rahim Uddin','Karim Hasan','Fatema Begum','Nusrat Jahan','Arif Ahmed'] as $i => $name) {
            $c = User::updateOrCreate(['email'=>'customer'.($i+1).'@mondals.com'],['name'=>$name,'password'=>bcrypt('password'),'status'=>'active','email_verified_at'=>now()]);
            $c->assignRole('customer');
            $customers[] = $c;
        }

        // ── Vendors ──
        $v1 = DB::table('vendors')->insertGetId([
            'user_id' => $vendor1->id,
            'store_name' => 'Mondal Electronics',
            'slug' => 'mondal-electronics',
            'description' => 'Best electronics store in Bangladesh',
            'phone' => '+8801711111111',
            'email' => 'vendor1@mondals.com',
            'city' => 'Dhaka',
            'country' => 'Bangladesh',
            'commission_rate' => 10.00,
            'status' => 'approved',
            'approved_at' => now(),
            'settings' => json_encode([
                'banking' => [
                    'bank_name' => 'City Bank PLC',
                    'account_name' => 'Mondal Electronics Ltd.',
                    'account_number' => '1201554488001'
                ]
            ]),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $v2 = DB::table('vendors')->insertGetId([
            'user_id' => $vendor2->id,
            'store_name' => 'Fashion Hub BD',
            'slug' => 'fashion-hub-bd',
            'description' => 'Trendy fashion for everyone',
            'phone' => '+8801722222222',
            'email' => 'vendor2@mondals.com',
            'city' => 'Chittagong',
            'country' => 'Bangladesh',
            'commission_rate' => 12.00,
            'status' => 'approved',
            'approved_at' => now(),
            'settings' => json_encode([
                'banking' => [
                    'bank_name' => 'Dutch Bangla Bank',
                    'account_name' => 'Fashion Hub Retail',
                    'account_number' => '554422110099'
                ]
            ]),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $v3 = DB::table('vendors')->insertGetId([
            'user_id' => $vendor3->id,
            'store_name' => 'Digital World',
            'slug' => 'digital-world',
            'description' => 'Gadgets and accessories',
            'phone' => '+8801733333333',
            'email' => 'vendor3@mondals.com',
            'city' => 'Sylhet',
            'country' => 'Bangladesh',
            'commission_rate' => 8.00,
            'status' => 'approved',
            'approved_at' => now(),
            'settings' => json_encode([
                'banking' => [
                    'bank_name' => 'Brac Bank',
                    'account_name' => 'Digital World Tech',
                    'account_number' => '9988776655'
                ]
            ]),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // ── Brands ──
        $brands = [];
        foreach ([['Samsung','samsung'],['Apple','apple'],['Xiaomi','xiaomi'],['Sony','sony'],['Nike','nike'],['Adidas','adidas'],['Levi\'s','levis'],['HP','hp'],['Dell','dell'],['Asus','asus']] as $b) {
            $brands[$b[1]] = DB::table('brands')->insertGetId(['name'=>$b[0],'slug'=>$b[1],'is_featured'=>rand(0,1),'is_active'=>1,'created_at'=>now(),'updated_at'=>now()]);
        }

        // ── Categories ──
        $cats = [];
        $catData = [
            ['Electronics','electronics',null],
            ['Fashion','fashion',null],
            ['Home & Living','home-living',null],
            ['Smartphones','smartphones','electronics'],
            ['Laptops','laptops','electronics'],
            ['TV & Audio','tv-audio','electronics'],
            ['Cameras','cameras','electronics'],
            ['Men\'s Clothing','mens-clothing','fashion'],
            ['Women\'s Clothing','womens-clothing','fashion'],
            ['Shoes','shoes','fashion'],
            ['Watches','watches','fashion'],
            ['Furniture','furniture','home-living'],
            ['Kitchen','kitchen','home-living'],
            ['Decor','decor','home-living'],
        ];
        foreach ($catData as $cd) {
            $cats[$cd[1]] = DB::table('categories')->insertGetId(['parent_id'=>$cd[2]?($cats[$cd[2]]??null):null,'name'=>$cd[0],'slug'=>$cd[1],'is_featured'=>rand(0,1),'is_active'=>1,'created_at'=>now(),'updated_at'=>now()]);
        }

        // ── Attributes ──
        $colorAttr = DB::table('attributes')->insertGetId(['name'=>'Color','slug'=>'color','type'=>'color','is_filterable'=>1,'is_active'=>1,'created_at'=>now(),'updated_at'=>now()]);
        $sizeAttr = DB::table('attributes')->insertGetId(['name'=>'Size','slug'=>'size','type'=>'select','is_filterable'=>1,'is_active'=>1,'created_at'=>now(),'updated_at'=>now()]);
        $storageAttr = DB::table('attributes')->insertGetId(['name'=>'Storage','slug'=>'storage','type'=>'select','is_filterable'=>1,'is_active'=>1,'created_at'=>now(),'updated_at'=>now()]);

        $colorVals = []; $sizeVals = []; $storageVals = [];
        foreach ([['Red','red','#FF0000'],['Blue','blue','#0000FF'],['Black','black','#000000'],['White','white','#FFFFFF'],['Green','green','#00FF00'],['Gold','gold','#FFD700']] as $cv) {
            $colorVals[$cv[1]] = DB::table('attribute_values')->insertGetId(['attribute_id'=>$colorAttr,'value'=>$cv[0],'slug'=>$cv[1],'color_code'=>$cv[2],'created_at'=>now(),'updated_at'=>now()]);
        }
        foreach (['S','M','L','XL','XXL'] as $sv) {
            $sizeVals[$sv] = DB::table('attribute_values')->insertGetId(['attribute_id'=>$sizeAttr,'value'=>$sv,'slug'=>strtolower($sv),'created_at'=>now(),'updated_at'=>now()]);
        }
        foreach (['64GB','128GB','256GB','512GB','1TB'] as $st) {
            $storageVals[$st] = DB::table('attribute_values')->insertGetId(['attribute_id'=>$storageAttr,'value'=>$st,'slug'=>strtolower(str_replace(' ','-',$st)),'created_at'=>now(),'updated_at'=>now()]);
        }

        // ── Products ──
        $products = [
            ['Samsung Galaxy S24 Ultra','samsung-galaxy-s24-ultra','SKU-SAM-001','Flagship smartphone','The Samsung Galaxy S24 Ultra features a stunning display and powerful camera system.',129999.00,149999.00,$v1,$brands['samsung'],$cats['smartphones'],true],
            ['iPhone 15 Pro Max','iphone-15-pro-max','SKU-APL-001','Premium Apple smartphone','Experience the latest Apple innovation with the iPhone 15 Pro Max.',159999.00,179999.00,$v1,$brands['apple'],$cats['smartphones'],true],
            ['Xiaomi 14 Pro','xiaomi-14-pro','SKU-XIA-001','Flagship killer','Xiaomi 14 Pro offers flagship features at a competitive price.',79999.00,89999.00,$v1,$brands['xiaomi'],$cats['smartphones'],false],
            ['HP Pavilion 15','hp-pavilion-15','SKU-HP-001','Powerful laptop for work','HP Pavilion 15 with Intel i7 processor and 16GB RAM.',89999.00,99999.00,$v3,$brands['hp'],$cats['laptops'],true],
            ['Dell XPS 13','dell-xps-13','SKU-DEL-001','Ultrabook','Dell XPS 13 - thin, light, and powerful ultrabook.',119999.00,139999.00,$v3,$brands['dell'],$cats['laptops'],false],
            ['Asus ROG Strix','asus-rog-strix','SKU-ASU-001','Gaming laptop','Asus ROG Strix gaming laptop with RTX 4060.',149999.00,169999.00,$v3,$brands['asus'],$cats['laptops'],true],
            ['Sony Bravia 55"','sony-bravia-55','SKU-SON-001','4K Smart TV','Sony Bravia 55 inch 4K HDR Smart TV.',79999.00,89999.00,$v1,$brands['sony'],$cats['tv-audio'],false],
            ['Nike Air Max 90','nike-air-max-90','SKU-NIK-001','Classic sneakers','Nike Air Max 90 - iconic design meets modern comfort.',12999.00,15999.00,$v2,$brands['nike'],$cats['shoes'],true],
            ['Adidas Ultraboost','adidas-ultraboost','SKU-ADI-001','Running shoes','Adidas Ultraboost - premium running shoes.',14999.00,17999.00,$v2,$brands['adidas'],$cats['shoes'],false],
            ['Levi\'s 501 Original','levis-501-original','SKU-LEV-001','Classic jeans','Levi\'s 501 Original Fit Jeans - timeless style.',5999.00,7999.00,$v2,$brands['levis'],$cats['mens-clothing'],true],
            ['Nike Dri-FIT T-Shirt','nike-dri-fit-tshirt','SKU-NIK-002','Sports t-shirt','Nike Dri-FIT moisture-wicking athletic t-shirt.',2999.00,3999.00,$v2,$brands['nike'],$cats['mens-clothing'],false],
            ['Samsung Galaxy Watch 6','samsung-galaxy-watch6','SKU-SAM-002','Smartwatch','Samsung Galaxy Watch 6 with health monitoring.',29999.00,34999.00,$v1,$brands['samsung'],$cats['watches'],true],
            ['Sony WH-1000XM5','sony-wh1000xm5','SKU-SON-002','Noise cancelling headphones','Sony WH-1000XM5 wireless noise cancelling headphones.',29999.00,34999.00,$v1,$brands['sony'],$cats['tv-audio'],true],
            ['Xiaomi Pad 6','xiaomi-pad-6','SKU-XIA-002','Android tablet','Xiaomi Pad 6 - 11 inch 144Hz display tablet.',34999.00,39999.00,$v3,$brands['xiaomi'],$cats['electronics'],false],
            ['Apple MacBook Air M3','apple-macbook-air-m3','SKU-APL-002','Apple laptop','MacBook Air with M3 chip - incredibly thin and powerful.',159999.00,179999.00,$v3,$brands['apple'],$cats['laptops'],true],
        ];

        $productIds = [];
        foreach ($products as $p) {
            $pid = DB::table('products')->insertGetId([
                'vendor_id'=>$p[7],'brand_id'=>$p[8],'category_id'=>$p[9],'name'=>$p[0],'slug'=>$p[1],'sku'=>$p[2],
                'short_description'=>$p[3],'description'=>$p[4],'price'=>$p[5],'compare_price'=>$p[6],
                'quantity'=>rand(10,200),'is_active'=>1,'is_featured'=>$p[10],'status'=>'approved','visibility'=>'public',
                'views_count'=>rand(50,5000),'sales_count'=>rand(5,500),'published_at'=>now(),'created_at'=>now(),'updated_at'=>now()
            ]);
            $productIds[] = $pid;
            DB::table('category_product')->insert(['category_id'=>$p[9],'product_id'=>$pid]);

            // Add dummy primary image
            DB::table('product_images')->insert([
                'product_id' => $pid,
                'image' => 'products/demo-' . $pid . '.jpg',
                'is_primary' => true,
                'sort_order' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // ── Product Variants (for first 3 phones) ──
        foreach ([$productIds[0],$productIds[1],$productIds[2]] as $idx => $phoneId) {
            DB::table('attribute_product')->insert([['attribute_id'=>$colorAttr,'product_id'=>$phoneId],['attribute_id'=>$storageAttr,'product_id'=>$phoneId]]);
            foreach (['black','white','gold'] as $ci => $color) {
                foreach (['128GB','256GB'] as $si => $storage) {
                    $vid = DB::table('product_variants')->insertGetId([
                        'product_id'=>$phoneId,'sku'=>"SKU-V-$idx-$ci-$si",'name'=>ucfirst($color)." / $storage",
                        'price'=>null,'quantity'=>rand(5,50),'is_active'=>1,'created_at'=>now(),'updated_at'=>now()
                    ]);
                    DB::table('product_variant_values')->insert([
                        ['product_variant_id'=>$vid,'attribute_id'=>$colorAttr,'attribute_value_id'=>$colorVals[$color]],
                        ['product_variant_id'=>$vid,'attribute_id'=>$storageAttr,'attribute_value_id'=>$storageVals[$storage]],
                    ]);
                }
            }
        }

        // ── Shoe variants (sizes) ──
        foreach ([$productIds[7],$productIds[8]] as $idx => $shoeId) {
            DB::table('attribute_product')->insert([['attribute_id'=>$sizeAttr,'product_id'=>$shoeId],['attribute_id'=>$colorAttr,'product_id'=>$shoeId]]);
            foreach (['black','white'] as $ci => $color) {
                foreach (['M','L','XL'] as $si => $size) {
                    $vid = DB::table('product_variants')->insertGetId([
                        'product_id'=>$shoeId,'sku'=>"SKU-SH-$idx-$ci-$si",'name'=>ucfirst($color)." / $size",
                        'price'=>null,'quantity'=>rand(5,30),'is_active'=>1,'created_at'=>now(),'updated_at'=>now()
                    ]);
                    DB::table('product_variant_values')->insert([
                        ['product_variant_id'=>$vid,'attribute_id'=>$colorAttr,'attribute_value_id'=>$colorVals[$color]],
                        ['product_variant_id'=>$vid,'attribute_id'=>$sizeAttr,'attribute_value_id'=>$sizeVals[$size]],
                    ]);
                }
            }
        }

        // ── Addresses for customers ──
        foreach ($customers as $i => $cust) {
            DB::table('addresses')->insert(['user_id'=>$cust->id,'label'=>'Home','first_name'=>explode(' ',$cust->name)[0],'last_name'=>explode(' ',$cust->name)[1]??'','phone'=>'+88017'.rand(10000000,99999999),'address_line_1'=>($i+1).'23 Mirpur Road','city'=>'Dhaka','zip_code'=>'1216','country'=>'Bangladesh','is_default_shipping'=>1,'is_default_billing'=>1,'created_at'=>now(),'updated_at'=>now()]);
        }

        // ── Coupons ──
        DB::table('coupons')->insert([
            ['vendor_id'=>null,'code'=>'WELCOME10','name'=>'Welcome 10% Off','type'=>'percentage','value'=>10,'min_order_amount'=>1000,'max_discount_amount'=>2000,'usage_limit'=>1000,'usage_limit_per_user'=>1,'times_used'=>42,'is_active'=>1,'starts_at'=>now()->subMonth(),'expires_at'=>now()->addMonths(3),'created_at'=>now(),'updated_at'=>now()],
            ['vendor_id'=>null,'code'=>'FLAT500','name'=>'Flat 500 Off','type'=>'fixed','value'=>500,'min_order_amount'=>5000,'max_discount_amount'=>null,'usage_limit'=>500,'usage_limit_per_user'=>2,'times_used'=>18,'is_active'=>1,'starts_at'=>now()->subWeek(),'expires_at'=>now()->addMonth(),'created_at'=>now(),'updated_at'=>now()],
            ['vendor_id'=>$v1,'code'=>'ELECTRO15','name'=>'Electronics 15% Off','type'=>'percentage','value'=>15,'min_order_amount'=>10000,'max_discount_amount'=>5000,'usage_limit'=>200,'usage_limit_per_user'=>1,'times_used'=>7,'is_active'=>1,'starts_at'=>now(),'expires_at'=>now()->addMonths(2),'created_at'=>now(),'updated_at'=>now()],
            ['vendor_id'=>null,'code'=>'FREESHIP','name'=>'Free Shipping','type'=>'free_shipping','value'=>0,'min_order_amount'=>3000,'max_discount_amount'=>null,'usage_limit'=>null,'usage_limit_per_user'=>null,'times_used'=>100,'is_active'=>1,'starts_at'=>now()->subMonths(2),'expires_at'=>now()->addMonths(6),'created_at'=>now(),'updated_at'=>now()],
        ]);

        // ── Tax Rates ──
        DB::table('tax_rates')->insert([
            ['name'=>'Bangladesh VAT','country'=>'Bangladesh','state'=>null,'zip_code'=>null,'rate'=>15.0000,'is_active'=>1,'priority'=>1,'is_compound'=>0,'created_at'=>now(),'updated_at'=>now()],
            ['name'=>'Dhaka City Tax','country'=>'Bangladesh','state'=>'Dhaka','zip_code'=>null,'rate'=>2.0000,'is_active'=>1,'priority'=>2,'is_compound'=>0,'created_at'=>now(),'updated_at'=>now()],
        ]);

        // ── Shipping ──
        $zone1 = DB::table('shipping_zones')->insertGetId(['name'=>'Inside Dhaka','is_active'=>1,'created_at'=>now(),'updated_at'=>now()]);
        $zone2 = DB::table('shipping_zones')->insertGetId(['name'=>'Outside Dhaka','is_active'=>1,'created_at'=>now(),'updated_at'=>now()]);
        DB::table('shipping_zone_regions')->insert([
            ['shipping_zone_id'=>$zone1,'country'=>'Bangladesh','state'=>'Dhaka','zip_code'=>null,'created_at'=>now(),'updated_at'=>now()],
            ['shipping_zone_id'=>$zone2,'country'=>'Bangladesh','state'=>null,'zip_code'=>null,'created_at'=>now(),'updated_at'=>now()],
        ]);
        DB::table('shipping_methods')->insert([
            ['shipping_zone_id'=>$zone1,'name'=>'Standard Delivery','type'=>'flat_rate','cost'=>60.00,'is_active'=>1,'sort_order'=>1,'created_at'=>now(),'updated_at'=>now(),'description'=>'2-3 business days','min_order_amount'=>null,'settings'=>null],
            ['shipping_zone_id'=>$zone1,'name'=>'Express Delivery','type'=>'flat_rate','cost'=>120.00,'is_active'=>1,'sort_order'=>2,'created_at'=>now(),'updated_at'=>now(),'description'=>'Same day delivery','min_order_amount'=>null,'settings'=>null],
            ['shipping_zone_id'=>$zone2,'name'=>'Nationwide Delivery','type'=>'flat_rate','cost'=>150.00,'is_active'=>1,'sort_order'=>1,'created_at'=>now(),'updated_at'=>now(),'description'=>'3-5 business days','min_order_amount'=>null,'settings'=>null],
            ['shipping_zone_id'=>$zone1,'name'=>'Free Shipping','type'=>'free','cost'=>0,'is_active'=>1,'sort_order'=>3,'created_at'=>now(),'updated_at'=>now(),'description'=>'For orders above 5000 BDT','min_order_amount'=>5000,'settings'=>null],
        ]);

        // ── Orders ──
        $orderStatuses = ['pending','confirmed','processing','shipped','delivered','completed'];
        for ($o = 1; $o <= 12; $o++) {
            $cust = $customers[array_rand($customers)];
            $statusIdx = rand(0, count($orderStatuses)-1);
            $status = $orderStatuses[$statusIdx];
            $subtotal = rand(5000,200000);
            $tax = round($subtotal*0.15,2);
            $shipping = [60,120,150][rand(0,2)];
            $discount = rand(0,1) ? rand(200,2000) : 0;
            $total = $subtotal + $tax + $shipping - $discount;

            $orderId = DB::table('orders')->insertGetId([
                'order_number'=>'ORD-'.str_pad($o,6,'0',STR_PAD_LEFT),'user_id'=>$cust->id,'status'=>$status,
                'payment_status'=>$statusIdx>=4?'paid':'pending','payment_method'=>['bkash','nagad','cod','card'][rand(0,3)],
                'subtotal'=>$subtotal,'discount_amount'=>$discount,'tax_amount'=>$tax,'shipping_amount'=>$shipping,'total'=>$total,
                'currency'=>'BDT','shipping_first_name'=>explode(' ',$cust->name)[0],'shipping_last_name'=>explode(' ',$cust->name)[1]??'',
                'shipping_address_line_1'=>$o.'00 Demo Street','shipping_city'=>'Dhaka','shipping_zip_code'=>'1216','shipping_country'=>'Bangladesh',
                'billing_first_name'=>explode(' ',$cust->name)[0],'billing_last_name'=>explode(' ',$cust->name)[1]??'',
                'billing_address_line_1'=>$o.'00 Demo Street','billing_city'=>'Dhaka','billing_zip_code'=>'1216','billing_country'=>'Bangladesh',
                'notes'=>null,'admin_notes'=>null,'shipping_method_name'=>'Standard Delivery',
                'paid_at'=>$statusIdx>=4?now()->subDays(rand(1,30)):null,'shipped_at'=>$statusIdx>=3?now()->subDays(rand(1,15)):null,
                'delivered_at'=>$statusIdx>=4?now()->subDays(rand(1,7)):null,'completed_at'=>$statusIdx>=5?now()->subDays(rand(1,3)):null,
                'created_at'=>now()->subDays(rand(1,60)),'updated_at'=>now()
            ]);

            $itemCount = rand(1,3);
            for ($it = 0; $it < $itemCount; $it++) {
                $pIdx = array_rand($productIds);
                $pId = $productIds[$pIdx];
                $prod = $products[$pIdx];
                $qty = rand(1,3);
                $price = $prod[5];
                $itemTotal = $price * $qty;
                DB::table('order_items')->insert([
                    'order_id'=>$orderId,'product_id'=>$pId,'vendor_id'=>$prod[7],'product_name'=>$prod[0],'sku'=>$prod[2],
                    'price'=>$price,'quantity'=>$qty,'subtotal'=>$itemTotal,'discount_amount'=>0,'tax_amount'=>round($itemTotal*0.15,2),
                    'total'=>$itemTotal+round($itemTotal*0.15,2),'created_at'=>now(),'updated_at'=>now()
                ]);
            }

            DB::table('order_status_history')->insert(['order_id'=>$orderId,'new_status'=>'pending','comment'=>'Order placed','created_at'=>now()->subDays(rand(30,60)),'updated_at'=>now()]);
            if ($statusIdx >= 1) DB::table('order_status_history')->insert(['order_id'=>$orderId,'old_status'=>'pending','new_status'=>'confirmed','comment'=>'Order confirmed','created_at'=>now()->subDays(rand(20,29)),'updated_at'=>now()]);

            // ── Vendor Earnings for this order ──
            $orderItems = DB::table('order_items')->where('order_id', $orderId)->get();
            foreach ($orderItems as $item) {
                $vendor = DB::table('vendors')->where('id', $item->vendor_id)->first();
                $commissionRate = $vendor ? $vendor->commission_rate : 10.00;
                $commissionAmount = round($item->subtotal * ($commissionRate / 100), 2);
                $vendorEarning = $item->subtotal - $commissionAmount;

                DB::table('vendor_earnings')->insert([
                    'vendor_id' => $item->vendor_id,
                    'order_id' => $orderId,
                    'order_item_id' => $item->id,
                    'order_item_total' => $item->subtotal,
                    'commission_rate' => $commissionRate,
                    'commission_amount' => $commissionAmount,
                    'vendor_earning' => $vendorEarning,
                    'is_paid' => ($statusIdx >= 5), // Only paid if status is 'completed'
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        // ── Seed some Payouts for Vendor 1 ──
        $payoutAmount = 5000.00;
        $payoutId = DB::table('vendor_payouts')->insertGetId([
            'vendor_id' => $v1,
            'payout_number' => 'PAY-' . Str::upper(Str::random(8)),
            'amount' => $payoutAmount,
            'net_amount' => $payoutAmount,
            'status' => 'completed',
            'payment_method' => 'Bank Transfer',
            'transaction_id' => 'TRX-' . rand(10000, 99999),
            'paid_at' => now()->subDays(5),
            'created_at' => now()->subDays(5),
            'updated_at' => now()->subDays(5)
        ]);
        // Link some earnings to this payout
        DB::table('vendor_earnings')
            ->where('vendor_id', $v1)
            ->where('is_paid', true)
            ->update(['vendor_payout_id' => $payoutId]);

        // ── Reviews ──
        $reviewData = [
            [1,'Amazing phone!','Best smartphone I have ever used. Camera quality is outstanding.',5],
            [1,'Good but expensive','Great features but the price is a bit high for Bangladesh market.',4],
            [3,'Value for money','Xiaomi never disappoints. Great specs at this price range.',5],
            [7,'Great TV','Picture quality is stunning. Smart features work well.',4],
            [9,'Perfect fit','These jeans are amazing quality. True to size.',5],
            [7,'Decent sound','Good TV but the built-in speakers could be better.',3],
            [12,'Love the watch','Battery lasts 2 days with heavy use. Health tracking is accurate.',4],
            [10,'Comfortable shoes','Very comfortable for daily wear and running.',5],
        ];
        foreach ($reviewData as $i => $r) {
            DB::table('reviews')->insert(['user_id'=>$customers[$i % count($customers)]->id,'product_id'=>$productIds[$r[0]-1],'rating'=>$r[3],'title'=>$r[1],'comment'=>$r[2],'status'=>'approved','approved_at'=>now(),'created_at'=>now()->subDays(rand(1,30)),'updated_at'=>now()]);
        }

        // ── Wishlists ──
        foreach ($customers as $cust) {
            $wishProducts = array_rand($productIds, rand(2,4));
            foreach ((array)$wishProducts as $wp) {
                DB::table('wishlists')->insert(['user_id'=>$cust->id,'product_id'=>$productIds[$wp],'created_at'=>now(),'updated_at'=>now()]);
            }
        }

        // ── CMS: Pages ──
        DB::table('pages')->insert([
            ['title'=>'About Us','slug'=>'about-us','content'=>'<h2>Welcome to Mondals Ecommerce</h2><p>We are Bangladesh\'s fastest-growing online marketplace.</p>','is_active'=>1,'meta_title'=>'About Mondals Ecommerce','created_at'=>now(),'updated_at'=>now(),'meta_description'=>null,'template'=>null,'sort_order'=>1],
            ['title'=>'Contact Us','slug'=>'contact-us','content'=>'<h2>Get in Touch</h2><p>Email: support@mondals.com | Phone: +880-1700-000000</p>','is_active'=>1,'meta_title'=>'Contact Us','created_at'=>now(),'updated_at'=>now(),'meta_description'=>null,'template'=>null,'sort_order'=>2],
            ['title'=>'Terms & Conditions','slug'=>'terms-conditions','content'=>'<h2>Terms of Service</h2><p>By using Mondals Ecommerce you agree to these terms...</p>','is_active'=>1,'meta_title'=>'Terms','created_at'=>now(),'updated_at'=>now(),'meta_description'=>null,'template'=>null,'sort_order'=>3],
            ['title'=>'Privacy Policy','slug'=>'privacy-policy','content'=>'<h2>Privacy Policy</h2><p>We value your privacy and protect your personal data...</p>','is_active'=>1,'meta_title'=>'Privacy Policy','created_at'=>now(),'updated_at'=>now(),'meta_description'=>null,'template'=>null,'sort_order'=>4],
        ]);

        // ── Banners ──
        DB::table('banners')->insert([
            ['title'=>'Mega Sale - Up to 50% Off','description'=>'Shop the biggest sale of the year','image'=>'banners/mega-sale.jpg','link'=>'/products?sale=1','position'=>'home_slider','is_active'=>1,'sort_order'=>1,'starts_at'=>now(),'expires_at'=>now()->addMonth(),'created_at'=>now(),'updated_at'=>now()],
            ['title'=>'New Arrivals','description'=>'Check out the latest products','image'=>'banners/new-arrivals.jpg','link'=>'/products?sort=newest','position'=>'home_slider','is_active'=>1,'sort_order'=>2,'starts_at'=>now(),'expires_at'=>now()->addMonths(3),'created_at'=>now(),'updated_at'=>now()],
            ['title'=>'Electronics Festival','description'=>'Best deals on electronics','image'=>'banners/electronics.jpg','link'=>'/categories/electronics','position'=>'home_banner','is_active'=>1,'sort_order'=>1,'starts_at'=>now(),'expires_at'=>now()->addMonth(),'created_at'=>now(),'updated_at'=>now()],
        ]);

        // ── Menus ──
        $headerMenu = DB::table('menus')->insertGetId(['name'=>'Main Navigation','slug'=>'main-nav','location'=>'header','is_active'=>1,'created_at'=>now(),'updated_at'=>now()]);
        $footerMenu = DB::table('menus')->insertGetId(['name'=>'Footer Menu','slug'=>'footer-menu','location'=>'footer','is_active'=>1,'created_at'=>now(),'updated_at'=>now()]);
        DB::table('menu_items')->insert([
            ['menu_id'=>$headerMenu,'parent_id'=>null,'title'=>'Home','url'=>'/','type'=>'custom','reference_id'=>null,'target'=>'_self','icon'=>null,'css_class'=>null,'sort_order'=>1,'is_active'=>1,'created_at'=>now(),'updated_at'=>now()],
            ['menu_id'=>$headerMenu,'parent_id'=>null,'title'=>'Electronics','url'=>'/categories/electronics','type'=>'category','reference_id'=>$cats['electronics'],'target'=>'_self','icon'=>null,'css_class'=>null,'sort_order'=>2,'is_active'=>1,'created_at'=>now(),'updated_at'=>now()],
            ['menu_id'=>$headerMenu,'parent_id'=>null,'title'=>'Fashion','url'=>'/categories/fashion','type'=>'category','reference_id'=>$cats['fashion'],'target'=>'_self','icon'=>null,'css_class'=>null,'sort_order'=>3,'is_active'=>1,'created_at'=>now(),'updated_at'=>now()],
            ['menu_id'=>$footerMenu,'parent_id'=>null,'title'=>'About Us','url'=>'/pages/about-us','type'=>'page','reference_id'=>null,'target'=>'_self','icon'=>null,'css_class'=>null,'sort_order'=>1,'is_active'=>1,'created_at'=>now(),'updated_at'=>now()],
            ['menu_id'=>$footerMenu,'parent_id'=>null,'title'=>'Contact','url'=>'/pages/contact-us','type'=>'page','reference_id'=>null,'target'=>'_self','icon'=>null,'css_class'=>null,'sort_order'=>2,'is_active'=>1,'created_at'=>now(),'updated_at'=>now()],
            ['menu_id'=>$footerMenu,'parent_id'=>null,'title'=>'Privacy Policy','url'=>'/pages/privacy-policy','type'=>'page','reference_id'=>null,'target'=>'_self','icon'=>null,'css_class'=>null,'sort_order'=>3,'is_active'=>1,'created_at'=>now(),'updated_at'=>now()],
        ]);

        // ── Blog ──
        $blogCat1 = DB::table('blog_categories')->insertGetId(['name'=>'Tech News','slug'=>'tech-news','is_active'=>1,'created_at'=>now(),'updated_at'=>now(),'description'=>null]);
        $blogCat2 = DB::table('blog_categories')->insertGetId(['name'=>'Fashion Tips','slug'=>'fashion-tips','is_active'=>1,'created_at'=>now(),'updated_at'=>now(),'description'=>null]);
        $blog1 = DB::table('blog_posts')->insertGetId(['user_id'=>1,'title'=>'Top 10 Smartphones in 2026','slug'=>'top-10-smartphones-2026','excerpt'=>'Our picks for the best phones this year.','content'=>'<p>Here are our top picks for the best smartphones you can buy in 2026...</p>','status'=>'published','views_count'=>1250,'published_at'=>now()->subDays(5),'created_at'=>now()->subDays(5),'updated_at'=>now()]);
        $blog2 = DB::table('blog_posts')->insertGetId(['user_id'=>1,'title'=>'Summer Fashion Trends 2026','slug'=>'summer-fashion-trends-2026','excerpt'=>'Stay stylish this summer.','content'=>'<p>Discover the hottest fashion trends for summer 2026...</p>','status'=>'published','views_count'=>890,'published_at'=>now()->subDays(3),'created_at'=>now()->subDays(3),'updated_at'=>now()]);
        DB::table('blog_category_post')->insert([['blog_category_id'=>$blogCat1,'blog_post_id'=>$blog1],['blog_category_id'=>$blogCat2,'blog_post_id'=>$blog2]]);

        // ── Settings ──
        $settings = [
            ['general','site_name','Mondals Ecommerce','text',1],
            ['general','site_tagline','Bangladesh\'s #1 Online Marketplace','text',1],
            ['general','site_email','support@mondals.com','text',0],
            ['general','site_phone','+880-1700-000000','text',1],
            ['general','site_address','Mirpur, Dhaka, Bangladesh','text',1],
            ['general','default_currency','BDT','text',1],
            ['seo','meta_title','Mondals Ecommerce - Shop Online in Bangladesh','text',1],
            ['seo','meta_description','Buy electronics, fashion, home appliances online in Bangladesh.','textarea',1],
            ['payment','cod_enabled','1','boolean',0],
            ['payment','bkash_enabled','1','boolean',0],
            ['payment','nagad_enabled','1','boolean',0],
            ['shipping','free_shipping_threshold','5000','number',0],
            ['email','order_confirmation','1','boolean',0],
            ['email','shipping_notification','1','boolean',0],
        ];
        foreach ($settings as $s) {
            DB::table('settings')->insert(['group'=>$s[0],'key'=>$s[1],'value'=>$s[2],'type'=>$s[3],'is_public'=>$s[4],'created_at'=>now(),'updated_at'=>now()]);
        }

        // ── Currencies ──
        DB::table('currencies')->insert([
            ['code'=>'BDT','name'=>'Bangladeshi Taka','symbol'=>'৳','exchange_rate'=>1.000000,'position'=>'before','decimal_places'=>2,'decimal_separator'=>'.','thousand_separator'=>',','is_default'=>1,'is_active'=>1,'created_at'=>now(),'updated_at'=>now()],
            ['code'=>'USD','name'=>'US Dollar','symbol'=>'$','exchange_rate'=>0.009100,'position'=>'before','decimal_places'=>2,'decimal_separator'=>'.','thousand_separator'=>',','is_default'=>0,'is_active'=>1,'created_at'=>now(),'updated_at'=>now()],
        ]);

        foreach (Order::orderBy('id')->take(3) as $order) {
            Notification::send($admin, new NewOrderAdminNotification($order));
        }
        $admin->notifications()->oldest()->first()?->markAsRead();

        $this->command->info('✅ Demo data seeded successfully!');
        $this->command->info('   Users: admin, staff, 3 vendors, 5 customers');
        $this->command->info('   Products: 15 | Orders: 12 | Reviews: 8 | Admin notifications: sample');
    }
}
