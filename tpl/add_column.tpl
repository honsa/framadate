{extends file='page.tpl'}

{block name="header"}
    <script type="text/javascript">
        window.date_formats = {
            DATE: '{__('Date', '%Y-%m-%d')}',
            DATEPICKER: '{__('Date', 'yyyy-mm-dd')}'
        };
    </script>
    <script type="text/javascript" src="{'js/app/framadatepicker.js'|resource}"></script>
{/block}

{block name=main}

    <form action="{poll_url id=$admin_poll_id admin=true}" method="POST">
        <div class="alert alert-info text-center">
            <h2>{__('adminstuds', 'Adding a column')}</h2>

            {* Messages *}
            {include 'part/messages.tpl'}

            {if $format === 'D'}
                <div class="form-group">
                    <label for="newdate" class="col-md-4">{__('Generic', 'Day')}</label>
                    <div class="col-md-8">
                        <div class="input-group date">
                            <span class="input-group-addon" aria-hidden="true">
                                <i class="fa fa-calendar-plus-o"></i>
                            </span>
                            <input type="text" id="newdate" data-date-format="{__('Date', 'yyyy-mm-dd')}" aria-describedby="dateformat" name="newdate" class="form-control" placeholder="{__('Date', 'yyyy-mm-dd-for-humans')}" />
                        </div>
                        <span id="dateformat" class="sr-only">({__('Date', 'yyyy-mm-dd')})</span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="newmoment" class="col-md-4">{__('Generic', 'Time')}</label>
                    <div class="col-md-8">
                        <input type="text" id="newmoment" name="newmoment" class="form-control" />
                    </div>
                </div>
            {else}
                <div class="form-group">
                    <label for="choice" class="col-md-4">{__('Generic', 'Choice')}</label>
                    <div class="col-md-8">
                        <input type="text" id="choice" name="choice" class="form-control" />
                    </div>
                </div>
            {/if}
            <div class="form-group">
                <a href="{poll_url id=$admin_poll_id admin=true}" class="btn btn-default" name="back">{__('adminstuds', 'Back to the poll')}</a>
                <button type="submit" name="confirm_add_column" class="btn btn-success">{__('adminstuds', 'Add a column')}</button>
            </div>
        </div>
    </form>
{/block}
