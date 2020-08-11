<h4>MAIA notifications</h4>
<p class="text-muted">
    Notifications when the state of one of your MAIA jobs changes.
</p>
<form id="maia-notification-settings">
    <div class="form-group">
        <label class="radio-inline">
            <input type="radio" v-model="settings" value="email"> <strong>Email</strong>
        </label>
        <label class="radio-inline">
            <input type="radio" v-model="settings" value="web"> <strong>Web</strong>
        </label>
        <span v-cloak>
            <loader v-if="loading" :active="true"></loader>
            <span v-else>
                <i v-if="saved" class="fa fa-check text-success"></i>
                <i v-if="error" class="fa fa-times text-danger"></i>
            </span>
        </span>
    </div>
</form>

@push('scripts')
<script type="text/javascript">
    biigle.$mount('maia-notification-settings', {
        mixins: [biigle.$require('core.mixins.loader')],
        data: {
            settings: '{!! $user->getSettings('maia_notifications', config('maia.notifications.default_settings')) !!}',
            saved: false,
            error: false,
        },
        methods: {
            handleSuccess: function () {
                this.saved = true;
                this.error = false;
            },
            handleError: function (response) {
                this.saved = false;
                this.error = true;
                biigle.$require('messages').handleErrorResponse(response);
            },
        },
        watch: {
            settings: function (settings) {
                this.startLoading();
                this.$http.put('api/v1/users/my/settings', {
                        maia_notifications: this.settings,
                    })
                    .then(this.handleSuccess, this.handleError)
                    .finally(this.finishLoading);
            },
        },
    });
</script>
@endpush
