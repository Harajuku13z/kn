<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample orders
        $order1 = Order::create([
            'collection_address' => '123 Rue de la République',
            'collection_address_complement' => 'Apt 5',
            'collection_city' => 'Brazzaville',
            'collection_postal_code' => '7500',
            'collection_date' => Carbon::now()->addDays(2),
            'collection_time_slot' => '08h-10h',
            'same_address_for_delivery' => true,
            'payment_method' => 'visa',
            'subtotal' => 45.00,
            'shipping_cost' => 10.00,
            'tax' => 5.00,
            'total' => 60.00,
            'payment_status' => 'paid',
            'order_status' => 'processing'
        ]);

        // Create items for the first order
        OrderItem::create([
            'order_id' => $order1->id,
            'item_id' => 'chemise',
            'item_name' => 'Chemise',
            'quantity' => 2,
            'price' => 10.00,
            'total' => 20.00
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'item_id' => 'jeans',
            'item_name' => 'Jeans',
            'quantity' => 1,
            'price' => 25.00,
            'total' => 25.00
        ]);

        // Create a second order
        $order2 = Order::create([
            'collection_address' => '456 Avenue des Champs',
            'collection_address_complement' => null,
            'collection_city' => 'Pointe-Noire',
            'collection_postal_code' => '7600',
            'collection_date' => Carbon::now()->addDays(5),
            'collection_time_slot' => '14h-16h',
            'same_address_for_delivery' => false,
            'delivery_address' => '789 Boulevard Central',
            'delivery_city' => 'Pointe-Noire',
            'delivery_postal_code' => '7600',
            'delivery_instructions' => 'Laisser chez le gardien',
            'payment_method' => 'mobile',
            'subtotal' => 35.00,
            'shipping_cost' => 10.00,
            'tax' => 5.00,
            'total' => 50.00,
            'payment_status' => 'pending',
            'order_status' => 'pending'
        ]);

        // Create items for the second order
        OrderItem::create([
            'order_id' => $order2->id,
            'item_id' => 'veste',
            'item_name' => 'Veste',
            'quantity' => 1,
            'price' => 15.00,
            'total' => 15.00
        ]);

        OrderItem::create([
            'order_id' => $order2->id,
            'item_id' => 'robe',
            'item_name' => 'Robe',
            'quantity' => 1,
            'price' => 20.00,
            'total' => 20.00
        ]);

        // Create a completed order
        $order3 = Order::create([
            'collection_address' => '10 Rue du Commerce',
            'collection_city' => 'Brazzaville',
            'collection_postal_code' => '7500',
            'collection_date' => Carbon::now()->subDays(10),
            'collection_time_slot' => '10h-12h',
            'same_address_for_delivery' => true,
            'payment_method' => 'airtel',
            'subtotal' => 70.00,
            'shipping_cost' => 10.00,
            'tax' => 5.00,
            'total' => 85.00,
            'payment_status' => 'paid',
            'order_status' => 'completed'
        ]);

        // Create items for the third order
        OrderItem::create([
            'order_id' => $order3->id,
            'item_id' => 'chemise',
            'item_name' => 'Chemise',
            'quantity' => 3,
            'price' => 10.00,
            'total' => 30.00
        ]);

        OrderItem::create([
            'order_id' => $order3->id,
            'item_id' => 'jeans',
            'item_name' => 'Jeans',
            'quantity' => 1,
            'price' => 25.00,
            'total' => 25.00
        ]);

        OrderItem::create([
            'order_id' => $order3->id,
            'item_id' => 'veste',
            'item_name' => 'Veste',
            'quantity' => 1,
            'price' => 15.00,
            'total' => 15.00
        ]);
    }
}
