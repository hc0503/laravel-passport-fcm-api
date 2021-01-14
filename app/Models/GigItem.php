<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

use App\Helpers\HasGuidTrait;

use Bavix\Wallet\Traits\HasWallet;
use Bavix\Wallet\Interfaces\Product;
use Bavix\Wallet\Interfaces\Customer;
use Bavix\Wallet\Interfaces\Taxable;

class GigItem extends Model implements Product
{
    use HasFactory, HasGuidTrait;
    use HasWallet;
    
    protected $table = 'gig_items';

    protected $fillable = [

    ];

    public function canBuy(Customer $customer, int $quantity = 1, bool $force = null): bool
    {
        /**
         * If the service can be purchased once, then
         *  return !$customer->paid($this);
         */
        return true; 
    }

    public function getAmountProduct(Customer $customer)
    {
        return $customer->amount;
    }

    public function getMetaProduct(): ?array
    {
        return [
            'type' => $this->meta_type,
            'gig' => [
                'id' => $this->guid,
                'title' => $this->title,
                'description' => $this->description,
                'created_at' => Carbon::parse($this->created_at)->toDateTimeString(),
                'updated_at' => Carbon::parse($this->updated_at)->toDateTimeString()
            ]
        ];
    }

    public function getUniqueId(): string
    {
        return (string)$this->getKey();
    }

    public function getFeePercent()
    {
        // return 0.03;    // 3%
        return 0.00;    // 0%
    }

    public function getMinimalFee()
    {
        // return 5;   //3%, minimum int(5)
        return 0.00;   // minimum int(0)
    }
}
