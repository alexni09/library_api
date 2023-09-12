<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase; 
use App\Models\Payment;
use App\Models\User;
use App\Models\Exemplar;
use Carbon\Carbon;
use Database\Seeders\ExemplarSeeder;
use Database\Seeders\CategorySeeder;

class PaymentTest extends TestCase {
    use RefreshDatabase;

    protected function setUp(): void {
        parent::setUp();
        (new CategorySeeder)->run();
        (new ExemplarSeeder)->run();
    }

    public function testAllPaymentsTotalIsSound() {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();
        $user4 = User::factory()->create();
        $user5 = User::factory()->create();
        $exemplars = Exemplar::where('borrowable',true)->limit(11)->get();
        Payment::create([
            'exemplar_id' => $exemplars[0]->id,
            'user_id' => $user1->id,
            'due_value' => 1,
            'due_from' => (Carbon::now())->subMinutes(30),
            'due_at' => (Carbon::now())->addMinutes(15)
        ]);
        Payment::create([
            'exemplar_id' => $exemplars[1]->id,
            'user_id' => $user1->id,
            'due_value' => 10,
            'due_from' => (Carbon::now())->subMinutes(30),
            'due_at' => (Carbon::now())->subMinutes(20)
        ]);
        Payment::create([
            'exemplar_id' => $exemplars[2]->id,
            'user_id' => $user1->id,
            'due_value' => 100,
            'due_from' => (Carbon::now())->subMinutes(30),
            'due_at' => (Carbon::now())->subMinutes(10)
        ]);
        Payment::create([
            'exemplar_id' => $exemplars[3]->id,
            'user_id' => $user1->id,
            'due_value' => 1000,
            'due_from' => (Carbon::now())->subMinutes(30),
            'due_at' => (Carbon::now())->addMinutes(45)
        ]);
        Payment::create([
            'exemplar_id' => $exemplars[4]->id,
            'user_id' => $user1->id,
            'due_value' => 10000,
            'due_from' => (Carbon::now())->subMinutes(30),
            'due_at' => (Carbon::now())->subMinutes(20),
            'paid_at' => (Carbon::now())->subMinutes(10)
        ]);
        Payment::create([
            'exemplar_id' => $exemplars[5]->id,
            'user_id' => $user1->id,
            'due_value' => 100000,
            'due_from' => (Carbon::now())->subMinutes(35),
            'due_at' => (Carbon::now())->subMinutes(25),
            'paid_at' => (Carbon::now())->subMinutes(15)
        ]);
        Payment::create([
            'exemplar_id' => $exemplars[6]->id,
            'user_id' => $user2->id,
            'due_value' => 20,
            'due_from' => (Carbon::now())->subMinutes(30),
            'due_at' => (Carbon::now())->addMinutes(12)
        ]);
        Payment::create([
            'exemplar_id' => $exemplars[7]->id,
            'user_id' => $user2->id,
            'due_value' => 2000,
            'due_from' => (Carbon::now())->subMinutes(30),
            'due_at' => (Carbon::now())->subMinutes(20)
        ]);
        Payment::create([
            'exemplar_id' => $exemplars[8]->id,
            'user_id' => $user2->id,
            'due_value' => 200000,
            'due_from' => (Carbon::now())->subMinutes(45),
            'due_at' => (Carbon::now())->subMinutes(25),
            'paid_at' => (Carbon::now())->subMinutes(15)
        ]);
        Payment::create([
            'exemplar_id' => $exemplars[9]->id,
            'user_id' => $user3->id,
            'due_value' => 30000,
            'due_from' => (Carbon::now())->subMinutes(30),
            'due_at' => (Carbon::now())->subMinutes(25)
        ]);
        Payment::create([
            'exemplar_id' => $exemplars[10]->id,
            'user_id' => $user4->id,
            'due_value' => 400000,
            'due_from' => (Carbon::now())->subMinutes(55),
            'due_at' => (Carbon::now())->subMinutes(30),
            'paid_at' => (Carbon::now())->subMinutes(15)
        ]);
        $this->assertEquals($user1->allPaymentsTotal(),111111);
        $this->assertEquals($user2->allPaymentsTotal(),202020);
        $this->assertEquals($user3->allPaymentsTotal(),30000);
        $this->assertEquals($user4->allPaymentsTotal(),400000);
        $this->assertEquals($user5->allPaymentsTotal(),0);
    }

    public function testTotalBalanceDueOpenIsSound() {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();
        $user4 = User::factory()->create();
        $exemplars = Exemplar::where('borrowable',true)->limit(11)->get();
        Payment::create([
            'exemplar_id' => $exemplars[0]->id,
            'user_id' => $user1->id,
            'due_value' => 1,
            'due_from' => (Carbon::now())->subMinutes(30),
            'due_at' => (Carbon::now())->addMinutes(15)
        ]);
        Payment::create([
            'exemplar_id' => $exemplars[1]->id,
            'user_id' => $user1->id,
            'due_value' => 10,
            'due_from' => (Carbon::now())->subMinutes(30),
            'due_at' => (Carbon::now())->subMinutes(20)
        ]);
        Payment::create([
            'exemplar_id' => $exemplars[2]->id,
            'user_id' => $user1->id,
            'due_value' => 100,
            'due_from' => (Carbon::now())->subMinutes(30),
            'due_at' => (Carbon::now())->subMinutes(10)
        ]);
        Payment::create([
            'exemplar_id' => $exemplars[3]->id,
            'user_id' => $user1->id,
            'due_value' => 1000,
            'due_from' => (Carbon::now())->subMinutes(30),
            'due_at' => (Carbon::now())->addMinutes(45)
        ]);
        Payment::create([
            'exemplar_id' => $exemplars[4]->id,
            'user_id' => $user1->id,
            'due_value' => 10000,
            'due_from' => (Carbon::now())->subMinutes(30),
            'due_at' => (Carbon::now())->subMinutes(20),
            'paid_at' => (Carbon::now())->subMinutes(10)
        ]);
        Payment::create([
            'exemplar_id' => $exemplars[5]->id,
            'user_id' => $user1->id,
            'due_value' => 100000,
            'due_from' => (Carbon::now())->subMinutes(35),
            'due_at' => (Carbon::now())->subMinutes(25),
            'paid_at' => (Carbon::now())->subMinutes(15)
        ]);
        Payment::create([
            'exemplar_id' => $exemplars[6]->id,
            'user_id' => $user2->id,
            'due_value' => 20,
            'due_from' => (Carbon::now())->subMinutes(30),
            'due_at' => (Carbon::now())->addMinutes(12)
        ]);
        Payment::create([
            'exemplar_id' => $exemplars[7]->id,
            'user_id' => $user2->id,
            'due_value' => 2000,
            'due_from' => (Carbon::now())->subMinutes(30),
            'due_at' => (Carbon::now())->subMinutes(20)
        ]);
        Payment::create([
            'exemplar_id' => $exemplars[8]->id,
            'user_id' => $user2->id,
            'due_value' => 200000,
            'due_from' => (Carbon::now())->subMinutes(45),
            'due_at' => (Carbon::now())->subMinutes(25),
            'paid_at' => (Carbon::now())->subMinutes(15)
        ]);
        Payment::create([
            'exemplar_id' => $exemplars[9]->id,
            'user_id' => $user3->id,
            'due_value' => 30000,
            'due_from' => (Carbon::now())->subMinutes(30),
            'due_at' => (Carbon::now())->subMinutes(25)
        ]);
        Payment::create([
            'exemplar_id' => $exemplars[10]->id,
            'user_id' => $user4->id,
            'due_value' => 400000,
            'due_from' => (Carbon::now())->subMinutes(55),
            'due_at' => (Carbon::now())->subMinutes(30),
            'paid_at' => (Carbon::now())->subMinutes(15)
        ]);
        $this->assertEquals($user1->balanceDueOpen(),110);
        $this->assertEquals($user2->balanceDueOpen(),2000);
        $this->assertEquals($user3->balanceDueOpen(),30000);
        $this->assertEquals($user4->balanceDueOpen(),0);
    }

    public function testTotalBalanceDueUnpaidIsSound() {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();
        $user4 = User::factory()->create();
        $exemplars = Exemplar::where('borrowable',true)->limit(6)->get();
        Payment::create([
            'exemplar_id' => $exemplars[0]->id,
            'user_id' => $user1->id,
            'due_value' => 1,
            'due_from' => (Carbon::now())->subMinutes(30),
            'due_at' => (Carbon::now())->subMinutes(20)
        ]);
        Payment::create([
            'exemplar_id' => $exemplars[1]->id,
            'user_id' => $user1->id,
            'due_value' => 10,
            'due_from' => (Carbon::now())->subMinutes(30),
            'due_at' => (Carbon::now())->subMinutes(20)
        ]);
        Payment::create([
            'exemplar_id' => $exemplars[2]->id,
            'user_id' => $user1->id,
            'due_value' => 100,
            'due_from' => (Carbon::now())->subMinutes(30),
            'due_at' => (Carbon::now())->subMinutes(20)
        ]);
        Payment::create([
            'exemplar_id' => $exemplars[3]->id,
            'user_id' => $user2->id,
            'due_value' => 20,
            'due_from' => (Carbon::now())->subMinutes(30),
            'due_at' => (Carbon::now())->subMinutes(20)
        ]);
        Payment::create([
            'exemplar_id' => $exemplars[4]->id,
            'user_id' => $user2->id,
            'due_value' => 2000,
            'due_from' => (Carbon::now())->subMinutes(30),
            'due_at' => (Carbon::now())->subMinutes(20)
        ]);
        Payment::create([
            'exemplar_id' => $exemplars[5]->id,
            'user_id' => $user3->id,
            'due_value' => 30000,
            'due_from' => (Carbon::now())->subMinutes(30),
            'due_at' => (Carbon::now())->subMinutes(20)
        ]);
        $this->assertEquals($user1->balanceDueUnpaid(),111);
        $this->assertEquals($user2->balanceDueUnpaid(),2020);
        $this->assertEquals($user3->balanceDueUnpaid(),30000);
        $this->assertEquals($user4->balanceDueUnpaid(),0);
    }
}