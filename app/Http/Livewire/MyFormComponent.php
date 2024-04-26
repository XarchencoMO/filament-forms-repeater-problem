<?php

namespace App\Http\Livewire;

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

    // Fields for payments
    public $remainingPaymentAmount = 1000;
    public array $payments;

    protected $rules = [
        'remainingPaymentAmount' => 'required|in:0',
    ];

    protected $messages = [
        'remainingPaymentAmount.in' => 'The unpaid order amount cannot be more than zero',
    ];

    protected function getFormSchema(): array
    {
        return [
            Repeater::make('payments')
                ->reactive()
                ->required(function () {
                    return $this->remainingPaymentAmount > 0;
                })
                ->schema([
                    Select::make('type')->required()
                        ->options(['test_1', 'test_2']),
                    TextInput::make('amount')
                        ->required()
                        ->numeric()
                        ->reactive()
                        ->maxValue(25000)

                ])->afterStateUpdated(function ($state) {
                    // TODO this method is not called
                    $this->remainingPaymentAmount = 1000 - array_sum(Arr::pluck($state, 'amount'));
                })
        ];
    }

    public function submit()
    {
        dd($this->form->getState());

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
