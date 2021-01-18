@extends('layouts.app')
   
@section('content')
<div class="container">
	<input id="card-holder-name" type="text">

	<!-- Stripe Elements Placeholder -->
	<div id="card-element"></div>

	<button id="card-button">
		Process Payment
	</button>
</div>

<script src="https://js.stripe.com/v3/"></script>

<script>
	 const stripe = Stripe('pk_test_8Lkxbgwp9xYXogbD9C7m3J3Y00zsapNHqw');

	 const elements = stripe.elements();
	 const cardElement = elements.create('card');

	 cardElement.mount('#card-element');
</script>

<script type="text/javascript">
	const cardHolderName = document.getElementById('card-holder-name');
	const cardButton = document.getElementById('card-button');

	cardButton.addEventListener('click', async (e) => {
		const { paymentMethod, error } = await stripe.createPaymentMethod(
			'card', cardElement, {
				billing_details: { name: cardHolderName.value }
			}
		);

		if (error) {
			// Display "error.message" to the user...
		} else {
			// The card has been verified successfully...
		}
	});
</script>

@endsection