
// Create an instance of the Stripe object with your publishable API key
var stripe = Stripe("pk_test_51HeLh9Ahufd1YJu1upvLnvCRa7zfk6gO6liv68Lxshy5wUv90Tt7W2oiNmNwHocTW0QUJG0TuELRquHfj5VoDt1S00ZiDe61WY");
var checkoutButton = document.getElementById("checkout-button");

checkoutButton.addEventListener("click", function () {
  fetch("/create-session", {
    method: "POST",
  })
    .then(function (response) {
      return response.json();
    })
    .then(function (session) {
      return stripe.redirectToCheckout({ sessionId: session.id });
    })
    .then(function (result) {
      // If redirectToCheckout fails due to a browser or network
      // error, you should display the localized error message to your
      // customer using error.message.
      if (result.error) {
        alert(result.error.message);
      }
    })
    .catch(function (error) {
      console.error("Error:", error);
    });
});