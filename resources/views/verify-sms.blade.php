<form method="post" action="{{ route('check-sms') }}">
    @csrf
    <label for="verification_code">Verification Code:</label>
    <input type="number" id="verification_code" name="verification_code" placeholder="Enter your verification code">
    <button type="submit">Verify Code</button>
</form>
 


