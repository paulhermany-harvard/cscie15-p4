<div id="confirm-delete" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Delete <span class="title">{{ $title }}</span>?</h3>
            </div>
            <div class="modal-body">
                <p class="message">{{ $message }}</p>
            </div>
            <div class="modal-footer">
                {{ Form::open(['method' => 'DELETE', 'url' => '#']) }}
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    {{ Form::submit('Delete', [
                        'class' => 'btn btn-danger btn-md delete'
                    ]) }}
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>