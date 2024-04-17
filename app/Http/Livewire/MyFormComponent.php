<?php

namespace App\Http\Livewire;

use App\Models\Customer;
use Closure;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Support\Arr;
use Livewire\Component;

class MyFormComponent extends Component implements HasForms
{
    use InteractsWithForms;

    // Поля для платежей
    public $remainingPaymentAmount = 1000;
    public array $payments;

    protected $rules = [
        'remainingPaymentAmount' => 'required|in:0',
    ];

    protected $messages = [
        'remainingPaymentAmount.in' => 'Неоплаченная сумма заказа не может быть больше нуля',
    ];

    protected function getFormModel(): string
    {
        return Customer::class;
    }

    protected function getFormSchema(): array
    {
        return [
            Repeater::make('payments')
                ->reactive()
                ->required(function () {
                    return $this->remainingPaymentAmount > 0;
                })
                ->schema([
//                    Select::make('type')->required()
//                        ->options(['test_1', 'test_2']),
                    TextInput::make('amount')
                        ->required()
                        ->numeric()
                        ->reactive()
                        ->maxValue(25000)
                        // TODO это костыль, по неизвестной мне причине afterStateUpdated не работает с Repeater-ом
                ])->afterStateUpdated(function ($state) {
                    $this->remainingPaymentAmount = 1000 - array_sum(Arr::pluck($state, 'amount'));
                })
        ];
    }

    public function submit()
    {
        dd($this->form->getState());


//        DB::transaction(function () {
//            Arr::map(Arr::get($this->form->getState(), 'payments'), function (array $item) {
//                // Создаю платеж
//                $this->order->payments()->create($item);
//            });
//            // Объявляю о завершении платежа (не подходит для случая оплаты интернет эквайринга)
//            event(new PaymentFinished($this->order));
//        });

        Notification::make()
            ->title(__('admin::common.success_create'))
            ->success()
            ->send();

        return redirect()->route('warehouse::admin.shipments.index');
    }

    public function render()
    {
        return view('livewire.my-form-component')->layout('layouts.app');
    }
}
