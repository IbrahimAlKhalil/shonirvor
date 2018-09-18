<button type="button" href="javascript:" class="btn btn-info w-100" data-toggle="modal" data-target="#notificationModal">নোটিফিকেশন পাঠান</button>
<!-- Modal -->
<div class="modal fade" id="notificationModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">নোটিফিকেশন</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('notification.send', $userId) }}" method="post">
                {{ csrf_field() }}
                <div class="modal-body">
                    <textarea name="notification" class="form-control" rows="4"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-primary">পাঠান</button>
                </div>
            </form>
        </div>
    </div>
</div>