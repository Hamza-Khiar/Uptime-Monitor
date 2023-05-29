<x-mail::message>
# Email Verification

Please click on the button to verify


<x-mail::button :url="$url.'/validated_user/'.$id" id="$id">
    Validate
</x-mail::button>


## Thanks, <br>

</x-mail::message>