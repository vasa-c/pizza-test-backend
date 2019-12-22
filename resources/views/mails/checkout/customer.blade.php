<?php
/** @var \App\Services\Orders\CheckoutResult $checkout */
?>
@extends('mails.layout')

@section('body')
    <p>Thanks for your order. We will contact you shortly.</p>
    <p>Your order number: <b>{{ $checkout->order->number }}</b></p>
    <p><a href="{{ $checkout->order->getCabinetLink() }}">Link to your order page</a></p>
    <p>Total price: {{ $checkout->order->total_price }} {{ $checkout->order->currency }}</p>

    @if ($checkout->isUserCreated())
        <br />
        <br />
        <p>Data of your account:</p>
        <p>E-mail: <b>{{ $checkout->user->email }}</b></p>
        <p>Password: <b>{{ $checkout->createdPassword }}</b></p>
    @endif
@endsection
