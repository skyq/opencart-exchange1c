<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-basic-exchange1c" data-toggle="tooltip"
                        title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>"
                   class="btn btn-default"><i class="fa fa-reply"></i></a>
                <a href="javascript:void(0)" data-toggle="tooltip" onclick="apply()" title="" class="btn btn-info"
                   data-original-title="<?php echo $button_apply; ?>"><i class="fa fa-refresh"></i></a></div>
            <script language="javascript">
                function apply() {
                    $('#form-basic-exchange1c').append('<input type="hidden" id="apply" name="apply" value="1" />');
                    $('#form-basic-exchange1c').submit();
                }
            </script>
            <h1><?php echo $heading_title; ?></h1>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid">
        <?php if ($error_warning) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
            </div>
            <div class="panel-body">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $text_tab_general; ?></a></li>
                    <li><a href="#tab-update-fields" data-toggle="tab"><?php echo $text_tab_update_fields; ?></a></li>
                    <li><a href="#tab_product" data-toggle="tab"><?php echo $text_tab_product; ?></a></li>
                    <li><a href="#tab_order" data-toggle="tab"><?php echo $text_tab_order; ?></a></li>
                    <li><a href="#tab_manual" data-toggle="tab"><?php echo $text_tab_manual; ?></a></li>
                </ul>
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data"
                      id="form-basic-exchange1c" class="form-horizontal">
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab-general">
                            <div class="form-group">
                                <label class="col-sm-2 control-label"
                                       for="input-status"><?php echo $entry_status; ?></label>
                                <div class="col-sm-10">
                                    <select name="exchange1c_status" id="input-status" class="form-control">
                                        <?php if ($exchange1c_status) { ?>
                                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                        <option value="0"><?php echo $text_disabled; ?></option>
                                        <?php } else { ?>
                                        <option value="1"><?php echo $text_enabled; ?></option>
                                        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"
                                       for="input-username"><?php echo $entry_username; ?></label>
                                <div class="col-sm-4">
                                    <input name="exchange1c_username" id="input-username" class="form-control"
                                           type="text" value="<?php echo $exchange1c_username; ?>"/>
                                </div>
                                <label class="col-sm-1 control-label"
                                       for="input-password"><?php echo $entry_password; ?></label>
                                <div class="col-sm-5">
                                    <input name="exchange1c_password" id="input-password" class="form-control"
                                           type="text" value="<?php echo $exchange1c_password; ?>"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-allow-ip"><span data-toggle="tooltip"
                                                                                                 title="<?php echo $help_allow_ip; ?>"><?php echo $entry_allow_ip; ?></span></label>
                                <div class="col-sm-10">
                                    <textarea rows="10" name="exchange1c_allow_ip" id="input-allow-ip"
                                              class="form-control"><?php echo $exchange1c_allow_ip; ?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-stock-status"><span data-toggle="tooltip" title="<?php echo $entry_stock_status_helper; ?>"><?php echo $entry_stock_status; ?></span></label>

                                <div class="col-sm-10">
                                    <select name="exchange1c_stock_status_id" id="input-stock-status" class="form-control">
                                        <?php foreach ($stock_statuses as $stock_status) { ?>
                                            <?php if ($stock_status['stock_status_id'] == $stock_status_id) { ?>
                                                <option value="<?php echo $stock_status['stock_status_id']; ?>" selected="selected"><?php echo $stock_status['name']; ?></option>
                                            <?php } else { ?>
                                                <option value="<?php echo $stock_status['stock_status_id']; ?>"><?php echo $stock_status['name']; ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"
                                       for="input-exchange1c-seo-url">
                                    <span data-toggle="tooltip"
                                          title="<?php echo $help_entry_seo_url; ?>"><?php echo $entry_seo_url; ?>
                                    </span>
                                </label>
                                <div class="col-sm-10">
                                    <label class="radio"><input type="checkbox" value="1"
                                                                       id="input-exchange1c-seo-url"
                                                                       name="exchange1c_seo_url" <?php echo ($exchange1c_seo_url == 1)? 'checked' : ''; ?>
                                        ></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"
                                       for="input-exchange1c-root-category-is-catalog">
                                    <span data-toggle="tooltip"
                                          title="<?php echo $help_entry_root_category_is_catalog; ?>"><?php echo $entry_root_category_is_catalog; ?>
                                    </span>
                                </label>
                                <div class="col-sm-10">
                                    <label class="radio"><input type="checkbox" value="1"
                                                                       id="input-exchange1c-root-category-is-catalog"
                                                                       name="exchange1c_root_category_is_catalog" <?php echo ($exchange1c_root_category_is_catalog == 1)? 'checked' : ''; ?>
                                        ></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"
                                       for="input-exchange1c-flush-product"><?php echo $entry_flush_product; ?></label>
                                <div class="col-sm-10">
                                    <label class="radio"><input type="checkbox" value="1"
                                                                       id="input-exchange1c-flush-product"
                                                                       name="exchange1c_flush_product" <?php echo ($exchange1c_flush_product == 1)? 'checked' : ''; ?>
                                        ></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"
                                       for="input-exchange1c-flush-category"><?php echo $entry_flush_category; ?></label>
                                <div class="col-sm-10">
                                    <label class="radio"><input type="checkbox" value="1"
                                                                       id="input-exchange1c-flush-category"
                                                                       name="exchange1c_flush_category" <?php echo ($exchange1c_flush_category == 1)? 'checked' : ''; ?>
                                        ></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"
                                       for="input-exchange1c-flush-manufacturer"><?php echo $entry_flush_manufacturer; ?></label>
                                <div class="col-sm-10">
                                    <label class="radio"><input type="checkbox" value="1"
                                                                       id="input-exchange1c-flush-manufacturer"
                                                                       name="exchange1c_flush_manufacturer" <?php echo ($exchange1c_flush_manufacturer == 1)? 'checked' : ''; ?>
                                        ></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"
                                       for="input-exchange1c-flush-attribute"><?php echo $entry_flush_attribute; ?></label>
                                <div class="col-sm-10">
                                    <label class="radio"><input type="checkbox" value="1"
                                                                       id="input-exchange1c-flush-attribute"
                                                                       name="exchange1c_flush_attribute" <?php echo ($exchange1c_flush_attribute == 1)? 'checked' : ''; ?>
                                        ></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"
                                       for="input-exchange1c-flush-quantity"><?php echo $entry_flush_quantity; ?></label>
                                <div class="col-sm-10">
                                    <label class="radio"><input type="checkbox" value="1"
                                                                       id="input-exchange1c-flush-quantity"
                                                                       name="exchange1c_flush_quantity" <?php echo ($exchange1c_flush_quantity == 1)? 'checked' : ''; ?>
                                        ></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"
                                       for="input-exchange1c-fill-parent-cats"><?php echo $entry_fill_parent_cats; ?></label>
                                <div class="col-sm-10">
                                    <label class="radio"><input type="checkbox" value="1"
                                                                       id="input-exchange1c-fill-parent-cats"
                                                                       name="exchange1c_fill_parent_cats" <?php echo ($exchange1c_fill_parent_cats == 1)? 'checked' : ''; ?>
                                        ></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"
                                       for="input-exchange1c-dont-use-artsync"><?php echo $entry_dont_use_artsync; ?></label>
                                <div class="col-sm-10">
                                    <label class="radio"><input type="checkbox" value="1"
                                                                       id="input-exchange1c-dont-use-artsync"
                                                                       name="exchange1c_dont_use_artsync" <?php echo ($exchange1c_dont_use_artsync == 1)? 'checked' : ''; ?>
                                        ></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"
                                       for="input-exchange1c-full-log"><?php echo $entry_full_log; ?></label>
                                <div class="col-sm-10">
                                    <label class="radio"><input type="checkbox" value="1"
                                                                       id="input-exchange1c-full-log"
                                                                       name="exchange1c_full_log" <?php echo ($exchange1c_full_log == 1)? 'checked' : ''; ?>
                                        ></label>
                                </div>
                            </div>
                            <div class="form-group hide">
                                <label class="col-sm-2 control-label"
                                       for="input-exchange1c-apply-watermark"><?php echo $entry_apply_watermark; ?></label>
                                <div class="col-sm-2">
                                    <label class="radio"><input type="checkbox" value="1"
                                                                       id="input-exchange1c-apply-watermark"
                                                                       name="exchange1c_apply_watermark"></label>
                                    <input type="hidden" name="exchange1c_watermark" value="" id="image"/>
                                </div>
                            </div>
                            <div class="form-group"></div>
                        </div>
                        <div class="tab-pane" id="tab-update-fields">
                            <div class="alert alert-success"><i class="fa fa-check-circle"></i><?php echo $text_update_fields_alert; ?></div>
                            <!-- GENERAL -->
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo $text_update_fields_general; ?></label>
                                <div class="col-sm-10">
                                    <div class="well well-sm" style="height: 350px; overflow: auto;">
                                        <?php if(isset($product_fields)){ ?>
                                            <?php foreach ($product_fields as $field) { ?>
                                                <div class="checkbox">
                                                    <label>
                                                        <?php if (in_array($field['name'], $exchange1c_update_fields)) { ?>
                                                        <input type="checkbox" name="exchange1c_update_fields[]" value="<?php echo $field['name']; ?>" checked="checked" />
                                                        <?php echo $field['label']; ?>
                                                        <?php } else { ?>
                                                        <input type="checkbox" name="exchange1c_update_fields[]" value="<?php echo $field['name']; ?>" />
                                                        <?php echo $field['label']; ?>
                                                        <?php } ?>
                                                    </label>
                                                </div>
                                            <?php } ?>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <!-- DESCRIPTION. -->
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo $text_update_fields_description; ?></label>
                                <div class="col-sm-10">
                                    <div class="well well-sm" style="height: 350px; overflow: auto;">
                                        <?php if(isset($product_description_fields)){ ?>
                                        <?php foreach ($product_description_fields as $desc_field) { ?>
                                        <div class="checkbox">
                                            <label>
                                                <?php if (in_array($desc_field['name'], $exchange1c_update_desc_fields)) { ?>
                                                <input type="checkbox" name="exchange1c_update_desc_fields[]" value="<?php echo $desc_field['name']; ?>" checked="checked" />
                                                <?php echo $desc_field['label']; ?>
                                                <?php } else { ?>
                                                <input type="checkbox" name="exchange1c_update_desc_fields[]" value="<?php echo $desc_field['name']; ?>" />
                                                <?php echo $desc_field['label']; ?>
                                                <?php } ?>
                                            </label>
                                        </div>
                                        <?php } ?>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="tab-pane" id="tab_product">
                            <div class="table-responsive " id="tab-product">
                                <table class="table table-bordered table-hover" id="exchange1c_price_type_id">
                                    <thead>
                                    <tr>
                                        <td class="text-left"><?php echo $entry_config_price_type; ?></td>
                                        <td class="text-left"><?php echo $entry_customer_group; ?></td>
                                        <td class="text-right"><?php echo $entry_quantity; ?></td>
                                        <td class="text-right"><?php echo $entry_priority; ?></td>
                                        <td></td>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    <?php $price_row = 0; ?>
                                    <?php foreach ($exchange1c_price_type as $obj) { ?>
                                    <?php if ($price_row == 0) { ?>
                                    <tr id="exchange1c_price_type_row<?php echo $price_row; ?>">
                                        <td class="left"><input type="text"
                                                                class="form-control"
                                                                name="exchange1c_price_type[<?php echo $price_row; ?>][keyword]"
                                                                value="<?php echo $obj['keyword']; ?>"/></td>
                                        <td class="left"><?php  echo $text_price_default; ?><input type="hidden"
                                                                                                   name="exchange1c_price_type[<?php echo $price_row; ?>][customer_group_id]"
                                                                                                   value="0"/></td>
                                        <td class="center">-<input type="hidden"
                                                                   name="exchange1c_price_type[<?php echo $price_row; ?>][quantity]"
                                                                   value="0"/></td>
                                        <td class="center">-<input type="hidden"
                                                                   name="exchange1c_price_type[<?php echo $price_row; ?>][priority]"
                                                                   value="0"/></td>
                                        <td class="left">&nbsp;</td>
                                    </tr>
                                    <?php } else { ?>
                                    <tr id="exchange1c_price_type_row<?php echo $price_row; ?>">
                                        <td class="left"><input type="text"
                                                                name="exchange1c_price_type[<?php echo $price_row; ?>][keyword]"
                                                                value="<?php echo $obj['keyword']; ?>"/></td>
                                        <td class="left"><select
                                                    name="exchange1c_price_type[<?php echo $price_row; ?>][customer_group_id]">
                                                <?php foreach ($customer_groups as $customer_group) { ?>
                                                <?php if ($customer_group['customer_group_id'] == $obj['customer_group_id']) { ?>
                                                <option value="<?php echo $customer_group['customer_group_id']; ?>"
                                                        selected="selected"><?php echo $customer_group['name']; ?></option>
                                                <?php } else { ?>
                                                <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                                                <?php } ?>
                                                <?php } ?>
                                            </select></td>
                                        <td class="center"><input type="text"
                                                                  name="exchange1c_price_type[<?php echo $price_row; ?>][quantity]"
                                                                  value="<?php echo $obj['quantity']; ?>" size="2"/>
                                        </td>
                                        <td class="center"><input type="text"
                                                                  name="exchange1c_price_type[<?php echo $price_row; ?>][priority]"
                                                                  value="<?php echo $obj['priority']; ?>" size="2"/>
                                        </td>
                                        <td class="center">
                                            <button type="button"
                                                    onclick="$('#exchange1c_price_type_row<?php echo $price_row; ?>').remove();"
                                                    data-toggle="tooltip" title="" class="btn btn-danger"
                                                    data-original-title="" aria-describedby=""><i
                                                        class="fa fa-minus-circle"></i></button>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                    <?php $price_row++; ?>
                                    <?php } ?>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td colspan="4"></td>
                                        <td class="left">
                                            <button type="button" onclick="addConfigPriceType();" data-toggle="tooltip"
                                                    title="" class="btn btn-primary" data-original-title=""><i
                                                        class="fa fa-plus-circle"></i></button>
                                        </td>
                                    </tr>
                                    </tfoot>

                                </table>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab_order">
                            <div class="alert alert-success"><i class="fa fa-check-circle"></i><?php echo $text_no_orders_support_alert; ?></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"
                                       for="input-order-status-to-exchange"><?php echo $entry_order_status_to_exchange; ?></label>
                                <div class="col-sm-10">
                                    <select name="exchange1c_order_status_to_exchange" id="input-order-status-to-exchange" class="form-control">
                                        <option value="0" <?php echo ($exchange1c_order_status_to_exchange == 0)? 'selected' : '' ;?>><?php echo $entry_order_status_to_exchange_not; ?></option>
                                        <?php foreach ($order_statuses as $order_status) { ?>
                                        <option value="<?php echo $order_status['order_status_id'];?>" <?php echo ($exchange1c_order_status_to_exchange == $order_status['order_status_id'])? 'selected' : '' ;?>><?php echo $order_status['name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"
                                       for="input-order-status"><?php echo $entry_order_status; ?></label>
                                <div class="col-sm-10">
                                    <select name="exchange1c_order_status" id="input-order-status" class="form-control">
                                        <?php foreach ($order_statuses as $order_status) { ?>
                                        <option value="<?php echo $order_status['order_status_id'];?>" <?php echo ($exchange1c_order_status == $order_status['order_status_id'])? 'selected' : '' ;?>><?php echo $order_status['name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"
                                       for="input-exchange1c-full-log"><?php echo $entry_full_log; ?></label>
                                <div class="col-sm-10">
                                    <label class="radio"><input type="checkbox" value="1"
                                                                       id="input-exchange1c-full-log"
                                                                       name="exchange1c_full_log" <?php echo ($exchange1c_full_log == 1)? 'checked' : ''; ?>
                                        ></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"
                                       for="input-exchange1c-order-currency"><?php echo $entry_order_currency; ?></label>
                                <div class="col-sm-4">
                                    <input name="exchange1c_order_currency" id="input-exchange1c-order-currency" class="form-control"
                                           type="text" value="<?php echo $exchange1c_order_currency; ?>"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"
                                       for="input-exchange1c-order-notify"><?php echo $entry_order_notify; ?></label>
                                <div class="col-sm-10">
                                    <label class="radio"><input type="checkbox" value="1"
                                                                       id="input-exchange1c-order-notify"
                                                                       name="exchange1c_order_notify" <?php echo ($exchange1c_order_notify == 1)? 'checked' : ''; ?>
                                        ></label>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab_manual">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="button-upload"><span data-toggle="tooltip" title="" data-original-title="<?php echo $text_max_filesize; ?>"><?php echo $entry_upload; ?></span></label>
                                <div class="col-sm-2">
                                    <button type="button" id="button-upload" data-loading-text="<?php echo $button_upload; ?>" class="btn btn-primary"><i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-offset-2 col-sm-10" id="progress-text"></div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript"><!--


    var price_row = <?php echo $price_row; ?>;

    function addConfigPriceType() {
        html = '';
        html += '  <tr id="exchange1c_price_type_row' + price_row + '">';
        html += '    <td class="left"><input class="form-control" type="text" name="exchange1c_price_type[' + price_row + '][keyword]" value="" /></td>';
        html += '    <td class="left"><select class="form-control" name="exchange1c_price_type[' + price_row + '][customer_group_id]">';
        <?php foreach($customer_groups as $customer_group) { ?>
            html += '      <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>';
        <?php } ?>
        html += '    </select></td>';
        html += '    <td class="center"><input class="form-control" type="text" name="exchange1c_price_type[' + price_row + '][quantity]" value="0" size="2" /></td>';
        html += '    <td class="center"><input class="form-control" type="text" name="exchange1c_price_type[' + price_row + '][priority]" value="0" size="2" /></td>';
//        html += '    <td class="center"><a onclick="$(\'#exchange1c_price_type_row' + price_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
        html += '    <td class="center"><a onclick="$(\'#exchange1c_price_type_row' + price_row + '\').remove();" data-toggle="tooltip" title="" class="btn btn-danger" data-original-title="<?php echo $button_remove; ?>"><i class="fa fa-minus-circle"></i></a></td>';
        html += '  </tr>';


//        $('#exchange1c_price_type_id tfoot').before(html);

        $('#exchange1c_price_type_id tbody').append(html);

        //$('#config_price_type_row' + price_row + ' .date').datepicker({dateFormat: 'yy-mm-dd'});
        price_row++;
    }
    //--></script>
<script type="text/javascript"><!--

    $('#button-upload').on('click', function() {
        $('#form-upload').remove();
        $('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>')
        $('#form-upload input[name=\'file\']').trigger('click');
        if (typeof timer != 'undefined') {
            clearInterval(timer);
        }
        timer = setInterval(function () {
            if ($('#form-upload input[name=\'file\']').val() != '') {
                clearInterval(timer);

                // Reset everything
                $('.alert').remove();
                $('#progress-text').html('');

                $.ajax({
                    url: 'index.php?route=extension/module/exchange1c/manualImport&token=<?php echo $token; ?>',
                    type: 'post',
                    dataType: 'json',
                    data: new FormData($('#form-upload')[0]),
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        $('#button-upload').button('loading');
                    },
                    complete: function () {
                        $('#button-upload').button('reset');
                    },
                    success: function (json) {
                        console.clear();
                        if (json['error']) {
                            $('#progress-text').html('<div class="alert alert-danger">' + json['error'] + '</div>');
                        }

                        if (json['success']) {
                            $('#progress-text').html('<div class="alert alert-success">' + json['success'] + '</div>');
                        }
                        if (json['debug']) {
                            console.log(json['debug']);
                        }
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });
            }
        }, 500);
    })
    //--></script>
<?php echo $footer; ?>
