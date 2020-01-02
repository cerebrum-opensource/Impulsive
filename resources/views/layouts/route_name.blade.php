<script>

// Route that used in js file
const FRONTEND_BID_ON_AUCTION = "{{ route('frontend_bid_on_auction') }}";
const FRONTEND_ADD_TO_RECALL = "{{ route('frontend_add_to_recall') }}";
const FRONTEND_LOGIN_CHECK = "{{ route('frontend_login_check') }}";
const FRONTEND_LIVE_AUCTIONS = "{{ route('frontend_auctions') }}";
const FRONTEND_SETUP_BID_AGENT = "{{ route('frontend_setup_bid_agent') }}";

const FRONTEND_LOGIN_CHECK_OVERVIEW = "{{ route('frontend_login_check_overview') }}";

// message that used in js file
const MESSAGE_TOKEN_EXPIRE = "{{ trans('message.session_token_expire') }}";
const MESSAGE_ALREADY_HIGHER_BIDDER = "{{ trans('message.already_higher_bidder') }}";
const MESSAGE_ALREADY_AUTO_BIDDER = "{{ trans('message.already_setup_auto_bidder') }}";
const MESSAGE_BIDLOCK = "{{ trans('message.bidlock') }}";
const MESSAGE_NO_LISTING_FOUND = "{{ trans('label.no_live_auctions') }}";
const CURRENCY_SYSMBOL_JS = "{{ CURRENCY_ICON }}";

const MESSAGE_EXPIRED_AUCTION = "{{ trans('message.already_expired_auction') }}";
const MESSAGE_BID_AGENT_SETUP = "{{ trans('message.bid_agent_is_setup') }}";


</script>
