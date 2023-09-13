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
use Illuminate\Support\Facades\DB;

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

    public function testUnauthenticatedCannotAccessAllPaymentsTotal() {
        $response = $this->getJson('/api/all-payments-total/');
        $response->assertStatus(401);
    }

    public function testUserReceivesAllPaymentsTotalCorrectly() {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $exemplars = Exemplar::where('borrowable',true)->limit(4)->get();
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
            'due_at' => (Carbon::now())->addMinutes(20)
        ]);
        Payment::create([
            'exemplar_id' => $exemplars[2]->id,
            'user_id' => $user1->id,
            'due_value' => 100,
            'due_from' => (Carbon::now())->subMinutes(30),
            'due_at' => (Carbon::now())->subMinutes(20),
            'paid_at' => (Carbon::now())->subMinutes(15)
        ]);
        Payment::create([
            'exemplar_id' => $exemplars[3]->id,
            'user_id' => $user2->id,
            'due_value' => 20,
            'due_from' => (Carbon::now())->subMinutes(30),
            'due_at' => (Carbon::now())->subMinutes(20)
        ]);
        $response = $this->actingAs($user1)->getJson('/api/all-payments-total/');
        $response->assertStatus(200)
            ->assertJsonStructure(['data'])
            ->assertJsonFragment([
                'all_payments_total' => 111
            ]);
    }

    public function testUnauthenticatedCannotAccessBalanceDueOpen() {
        $response = $this->getJson('/api/balance-due-open/');
        $response->assertStatus(401);
    }

    public function testUserReceivesBalanceDueOpenCorrectly() {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $exemplars = Exemplar::where('borrowable',true)->limit(4)->get();
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
            'due_at' => (Carbon::now())->addMinutes(20)
        ]);
        Payment::create([
            'exemplar_id' => $exemplars[2]->id,
            'user_id' => $user1->id,
            'due_value' => 100,
            'due_from' => (Carbon::now())->subMinutes(30),
            'due_at' => (Carbon::now())->subMinutes(20),
            'paid_at' => (Carbon::now())->subMinutes(15)
        ]);
        Payment::create([
            'exemplar_id' => $exemplars[3]->id,
            'user_id' => $user2->id,
            'due_value' => 20,
            'due_from' => (Carbon::now())->subMinutes(30),
            'due_at' => (Carbon::now())->subMinutes(20)
        ]);
        $response = $this->actingAs($user1)->getJson('/api/balance-due-open/');
        $response->assertStatus(200)
            ->assertJsonStructure(['data'])
            ->assertJsonFragment([
                'balance_due_open' => 1
            ]);
    }

    public function testUnauthenticatedCannotAccessBalanceDueUnpaid() {
        $response = $this->getJson('/api/balance-due-unpaid/');
        $response->assertStatus(401);
    }

    public function testUserReceivesBalanceDueUnpaidCorrectly() {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $exemplars = Exemplar::where('borrowable',true)->limit(4)->get();
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
            'due_at' => (Carbon::now())->addMinutes(20)
        ]);
        Payment::create([
            'exemplar_id' => $exemplars[2]->id,
            'user_id' => $user1->id,
            'due_value' => 100,
            'due_from' => (Carbon::now())->subMinutes(30),
            'due_at' => (Carbon::now())->subMinutes(20),
            'paid_at' => (Carbon::now())->subMinutes(15)
        ]);
        Payment::create([
            'exemplar_id' => $exemplars[3]->id,
            'user_id' => $user2->id,
            'due_value' => 20,
            'due_from' => (Carbon::now())->subMinutes(30),
            'due_at' => (Carbon::now())->subMinutes(20)
        ]);
        $response = $this->actingAs($user1)->getJson('/api/balance-due-unpaid/');
        $response->assertStatus(200)
            ->assertJsonStructure(['data'])
            ->assertJsonFragment([
                'balance_due_unpaid' => 11
            ]);
    }

    public function testUnauthenticatedCannotAccessListBalanceDueOpen() {
        $response = $this->getJson('/api/list-balance-due-open/');
        $response->assertStatus(401);
    }

    public function testUserReceivesListBalanceDueOpenCorrectly() {
        $user = User::factory()->create();
        $exemplars = Exemplar::where('borrowable',true)->limit(3)->get();
        $past30 = (Carbon::now())->subMinutes(30);
        $past20 = (Carbon::now())->subMinutes(20);
        $future20 = (Carbon::now())->addMinutes(20);
        $past25 = (Carbon::now())->subMinutes(25);
        Payment::create([
            'exemplar_id' => $exemplars[0]->id,
            'user_id' => $user->id,
            'due_value' => 2,
            'due_from' => $past30,
            'due_at' => $past20
        ]);
        Payment::create([
            'exemplar_id' => $exemplars[1]->id,
            'user_id' => $user->id,
            'due_value' => 20,
            'due_from' => $past30,
            'due_at' => $future20
        ]);
        Payment::create([
            'exemplar_id' => $exemplars[2]->id,
            'user_id' => $user->id,
            'due_value' => 200,
            'due_from' => $past30,
            'due_at' => $past20,
            'paid_at' => $past25
        ]);
        $response = $this->actingAs($user)->getJson('/api/list-balance-due-open/');
        $response->assertStatus(200)
            ->assertJsonStructure(['data'])
            ->assertJsonCount(1,'data')
            ->assertJsonFragment([
                'exemplar_id' => $exemplars[0]->id,
                'due_value' => 2,
                'due_from' => $past30->toDateTimeString(),
                'due_at' => $past20->toDateTimeString(),
                'paid_at' => null
            ]);
    }

    public function testUnauthenticatedCannotAccessListBalanceDueUnpaid() {
        $response = $this->getJson('/api/list-balance-due-unpaid/');
        $response->assertStatus(401);
    }

    public function testUserReceivesListBalanceDueUnpaidCorrectly() {
        $user = User::factory()->create();
        $exemplars = Exemplar::where('borrowable',true)->limit(3)->get();
        $past30 = (Carbon::now())->subMinutes(30);
        $past20 = (Carbon::now())->subMinutes(20);
        $future20 = (Carbon::now())->addMinutes(20);
        $past25 = (Carbon::now())->subMinutes(25);
        Payment::create([
            'exemplar_id' => $exemplars[0]->id,
            'user_id' => $user->id,
            'due_value' => 2,
            'due_from' => $past30,
            'due_at' => $past20
        ]);
        Payment::create([
            'exemplar_id' => $exemplars[1]->id,
            'user_id' => $user->id,
            'due_value' => 20,
            'due_from' => $past30,
            'due_at' => $future20
        ]);
        Payment::create([
            'exemplar_id' => $exemplars[2]->id,
            'user_id' => $user->id,
            'due_value' => 200,
            'due_from' => $past30,
            'due_at' => $past20,
            'paid_at' => $past25
        ]);
        $response = $this->actingAs($user)->getJson('/api/list-balance-due-unpaid/');
        $response->assertStatus(200)
            ->assertJsonStructure(['data'])
            ->assertJsonCount(2,'data')
            ->assertJsonFragment([
                'exemplar_id' => $exemplars[0]->id,
                'due_value' => 2,
                'due_from' => $past30->toDateTimeString(),
                'due_at' => $past20->toDateTimeString(),
                'paid_at' => null
            ])
            ->assertJsonFragment([
                'exemplar_id' => $exemplars[1]->id,
                'due_value' => 20,
                'due_from' => $past30->toDateTimeString(),
                'due_at' => $future20->toDateTimeString(),
                'paid_at' => null
            ]);
    }

    public function testUnauthenticatedCannotMakeAPayment() {
        $user = User::factory()->create();
        $exemplar = Exemplar::where('borrowable',true)->first();
        $payment = Payment::create([
            'exemplar_id' => $exemplar->id,
            'user_id' => $user->id,
            'due_value' => 223455,
            'due_from' => (Carbon::now())->subMinutes(50),
            'due_at' => (Carbon::now())->subMinutes(10)
        ]);
        $response = $this->patchJson('/api/pay/' . strval($payment->id), [ 'money' => 345345]);
        $response->assertStatus(401);
    }

    public function testUserCannotPayIfAlreadyPaid() {
        $user = User::factory()->create();
        $exemplar = Exemplar::where('borrowable',true)->first();
        $payment = Payment::create([
            'exemplar_id' => $exemplar->id,
            'user_id' => $user->id,
            'due_value' => 223455,
            'due_from' => (Carbon::now())->subMinutes(50),
            'due_at' => (Carbon::now())->subMinutes(30),
            'paid_at' => (Carbon::now())->subMinutes(10)
        ]);
        $response = $this->actingAs($user)->patchJson('/api/pay/' . strval($payment->id), [ 'money' => 345345]);
        $response->assertStatus(422);
    }

    public function testUserCannotPaySomeoneElses() {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $exemplar = Exemplar::where('borrowable',true)->first();
        $payment = Payment::create([
            'exemplar_id' => $exemplar->id,
            'user_id' => $user1->id,
            'due_value' => 223455,
            'due_from' => (Carbon::now())->subMinutes(50),
            'due_at' => (Carbon::now())->subMinutes(30)
        ]);
        $response = $this->actingAs($user2)->patchJson('/api/pay/' . strval($payment->id), [ 'money' => 345345]);
        $response->assertStatus(403);
    }

    public function testUserCannotUnderpay() {
        $user = User::factory()->create();
        $exemplar = Exemplar::where('borrowable',true)->first();
        $payment = Payment::create([
            'exemplar_id' => $exemplar->id,
            'user_id' => $user->id,
            'due_value' => 223455,
            'due_from' => (Carbon::now())->subMinutes(50),
            'due_at' => (Carbon::now())->subMinutes(30)
        ]);
        $response = $this->actingAs($user)->patchJson('/api/pay/' . strval($payment->id), [ 'money' => 45]);
        $response->assertStatus(422);
    }

    public function testUserCanPayCorrectly() {
        $user = User::factory()->create();
        $exemplar = Exemplar::where('borrowable',true)->first();
        $payment = Payment::create([
            'exemplar_id' => $exemplar->id,
            'user_id' => $user->id,
            'due_value' => 123456,
            'due_from' => (Carbon::now())->subMinutes(40),
            'due_at' => (Carbon::now())->subMinutes(25)
        ]);
        $response = $this->actingAs($user)->patchJson('/api/pay/' . strval($payment->id), [ 'money' => 234567]);
        $response->assertStatus(200)
            ->assertJsonStructure(['data'])
            ->assertJsonFragment([
                'id' => $payment->id,
                'exemplar_id' => $exemplar->id,
                'due_value' => 123456,
                'received' => 234567,
                'change' => 111111,
                'due_from' => $payment->due_from->toDateTimeString(),
                'due_at' => $payment->due_at->toDateTimeString()
            ]);
        $flag = (bool) DB::table('payments')->selectRaw('count(*) as qty')->where('id',$payment->id)->whereNotNull('paid_at')->get()[0]->qty;
        $this->assertTrue($flag);
    }
}