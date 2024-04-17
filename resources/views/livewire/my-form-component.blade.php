<div class="mx-3 my-4">
    <p class="text-sm font-normal text-gray-500">Оставшаяся сумма: {{$this->remainingPaymentAmount}}</p>
    <form wire:submit.prevent="submit">
        {{ $this->form }}
        <div>
            <button type="submit">save</button>
        </div>
    </form>
</div>
