<?php

/**
 * Created by PhpStorm.
 * User: uSkyQ
 * Date: 22.03.2017
 */
class ControllerExtensionModuleExchange1c extends Controller
{

    private $error = [];

    public function index()
    {
        $this->load->language('extension/module/exchange1c');
        $this->load->model('setting/setting');
        $this->load->model('tool/image');
        $this->document->setTitle($this->language->get('heading_title'));

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            if (isset($this->request->post['exchange1c_update_fields'])) {
                $this->request->post['exchange1c_update_fields'] = json_encode($this->request->post['exchange1c_update_fields']);
            }
            if (isset($this->request->post['exchange1c_update_desc_fields'])) {
                $this->request->post['exchange1c_update_desc_fields'] = json_encode($this->request->post['exchange1c_update_desc_fields']);
            }
            $this->model_setting_setting->editSetting('exchange1c', $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');
            if (isset($this->request->post['apply']) and $this->request->post['apply']) {
                $this->response->redirect($this->url->link('extension/module/exchange1c', 'token=' . $this->session->data['token'], true));
            } else {
                $this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true));
            }
        }


        $data = $this->loadLanguage();
        $data = $this->loadBreadcrumbs($data);
        $data = $this->loadMainData($data);
        $data = $this->loadAction($data);

        $this->loadTemplate($data);

    }

    public function install()
    {
        if ($this->user->hasPermission('modify', 'extension/extension')) {
            $this->load->model('extension/exchange1c');
            $this->model_extension_exchange1c->install();
        }
    }

    public function uninstall()
    {
        $this->load->model('extension/exchange1c');
        $this->model_extension_exchange1c->uninstall();
    }

    public function deleteProductAfter(&$route, &$data)
    {
        if ($this->config->get('exchange1c_status')) {
            if ($this->config->get('exchange1c_full_log')) {
                $this->log->write('controller deleteProductAfter');
            }
            $this->load->model('extension/exchange1c');
            $product_id = $data[0];
            $this->model_extension_exchange1c->deleteProductAfter($product_id);
        }
    }

    public function deleteCategoryAfter(&$route, &$data)
    {
        if ($this->config->get('exchange1c_status')) {
            if ($this->config->get('exchange1c_full_log')) {
                $this->log->write('controller deleteCategoryAfter');
            }
            $this->load->model('extension/exchange1c');

            $category_id = $data[0];
            $this->model_extension_exchange1c->deleteCategoryAfter($category_id);
        }
    }

    private function loadLanguage()
    {
        $data = [];
        $data['heading_title'] = $this->language->get('heading_title');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['button_save'] = $this->language->get('button_save');
        $data['text_edit'] = $this->language->get('text_edit');

        $data['version'] = 'Version 2.3.0.2.1';

        $data['help_entry_root_category_is_catalog'] = $this->language->get('help_entry_root_category_is_catalog');
        $data['entry_root_category_is_catalog'] = $this->language->get('entry_root_category_is_catalog');
        $data['entry_stock_status_helper'] = $this->language->get('entry_stock_status_helper');
        $data['entry_flush_manufacturer'] = $this->language->get('entry_flush_manufacturer');
        $data['entry_config_price_type'] = $this->language->get('entry_config_price_type');
        $data['entry_fill_parent_cats'] = $this->language->get('entry_fill_parent_cats');
        $data['entry_seo_url_translit'] = $this->language->get('entry_seo_url_translit');
        $data['entry_flush_attribute'] = $this->language->get('entry_flush_attribute');
        $data['entry_seo_url_deadcow'] = $this->language->get('entry_seo_url_deadcow');
        $data['entry_apply_watermark'] = $this->language->get('entry_apply_watermark');
        $data['entry_flush_quantity'] = $this->language->get('entry_flush_quantity');
        $data['entry_customer_group'] = $this->language->get('entry_customer_group');
        $data['entry_flush_category'] = $this->language->get('entry_flush_category');
        $data['entry_flush_product'] = $this->language->get('entry_flush_product');
        $data['entry_stock_status'] = $this->language->get('entry_stock_status');
        $data['text_price_default'] = $this->language->get('text_price_default');
        $data['text_image_manager'] = $this->language->get('text_image_manager');
        $data['entry_username'] = $this->language->get('entry_username');
        $data['entry_password'] = $this->language->get('entry_password');
        $data['entry_allow_ip'] = $this->language->get('entry_allow_ip');
        $data['entry_quantity'] = $this->language->get('entry_quantity');
        $data['entry_priority'] = $this->language->get('entry_priority');
        $data['entry_full_log'] = $this->language->get('entry_full_log');
        $data['help_allow_ip'] = $this->language->get('help_allow_ip');
        $data['entry_seo_url'] = $this->language->get('entry_seo_url');
        $data['text_browse'] = $this->language->get('text_browse');
        $data['entry_image'] = $this->language->get('entry_image');
        $data['text_clear'] = $this->language->get('text_clear');
        $data['entry_name'] = $this->language->get('entry_name');
        $data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);


        $data['entry_relatedoptions'] = $this->language->get('entry_relatedoptions');
        $data['entry_dont_use_artsync'] = $this->language->get('entry_dont_use_artsync');
        $data['entry_relatedoptions_help'] = $this->language->get('entry_relatedoptions_help');
        $data['text_no_orders_support_alert'] = $this->language->get('text_no_orders_support_alert');
        $data['entry_order_status_to_exchange'] = $this->language->get('entry_order_status_to_exchange');
        $data['entry_order_status_to_exchange_not'] = $this->language->get('entry_order_status_to_exchange_not');

        $data['text_update_fields_description'] = $this->language->get('text_update_fields_description');
        $data['text_update_fields_general'] = $this->language->get('text_update_fields_general');
        $data['text_update_fields_alert'] = $this->language->get('text_update_fields_alert');
        $data['text_tab_update_fields'] = $this->language->get('text_tab_update_fields');
        $data['entry_order_currency'] = $this->language->get('entry_order_currency');
        $data['entry_order_status'] = $this->language->get('entry_order_status');
        $data['entry_order_notify'] = $this->language->get('entry_order_notify');
        $data['text_max_filesize'] = sprintf($this->language->get('text_max_filesize'), @ini_get('max_file_uploads'));
        $data['text_tab_general'] = $this->language->get('text_tab_general');
        $data['text_tab_product'] = $this->language->get('text_tab_product');
        $data['text_tab_manual'] = $this->language->get('text_tab_manual');
        $data['text_tab_order'] = $this->language->get('text_tab_order');
        $data['text_homepage'] = $this->language->get('text_homepage');
        $data['source_code'] = $this->language->get('source_code');
        $data['text_empty'] = $this->language->get('text_empty');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');

        $data['entry_upload'] = $this->language->get('entry_upload');
        $data['button_apply'] = $this->language->get('button_apply');
        $data['button_upload'] = $this->language->get('button_upload');

        $data['button_insert'] = $this->language->get('button_insert');
        $data['button_remove'] = $this->language->get('button_remove');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }
        if (isset($this->error['image'])) {
            $data['error_image'] = $this->error['image'];
        } else {
            $data['error_image'] = '';
        }
        if (isset($this->error['exchange1c_username'])) {
            $data['error_exchange1c_username'] = $this->error['exchange1c_username'];
        } else {
            $data['error_exchange1c_username'] = '';
        }


        return $data;
    }

    private function loadBreadcrumbs($data)
    {
        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module/exchange1c', 'token=' . $this->session->data['token'], true),
        ];
        return $data;
    }

    private function loadMainData($data)
    {
        $this->load->model('extension/exchange1c');
        $this->load->model('customer/customer_group');
        $this->load->model('localisation/order_status');
        $this->load->language('catalog/product');

        if (isset($this->request->post['exchange1c_status'])) {
            $data['exchange1c_status'] = $this->request->post['exchange1c_status'];
        } else {
            $data['exchange1c_status'] = $this->config->get('exchange1c_status');
        }

        $data['token'] = $this->session->data['token'];
        if (isset($this->request->post['exchange1c_username'])) {
            $data['exchange1c_username'] = $this->request->post['exchange1c_username'];
        } else {
            $data['exchange1c_username'] = $this->config->get('exchange1c_username');
        }

        if (isset($this->request->post['exchange1c_password'])) {
            $data['exchange1c_password'] = $this->request->post['exchange1c_password'];
        } else {
            $data['exchange1c_password'] = $this->config->get('exchange1c_password');
        }

        if (isset($this->request->post['exchange1c_stock_status_id'])) {
            $data['stock_status_id'] = $this->request->post['exchange1c_stock_status_id'];
        } else {
            $data['stock_status_id'] = $this->config->get('exchange1c_stock_status_id');
        }

        $this->load->model('localisation/stock_status');
        $data['stock_statuses'] = $this->model_localisation_stock_status->getStockStatuses();

        if (isset($this->request->post['exchange1c_allow_ip'])) {
            $data['exchange1c_allow_ip'] = $this->request->post['exchange1c_allow_ip'];
        } else {
            $data['exchange1c_allow_ip'] = $this->config->get('exchange1c_allow_ip');
        }

        if (isset($this->request->post['exchange1c_status'])) {
            $data['exchange1c_status'] = $this->request->post['exchange1c_status'];
        } else {
            $data['exchange1c_status'] = $this->config->get('exchange1c_status');
        }

        if (isset($this->request->post['exchange1c_price_type'])) {
            $data['exchange1c_price_type'] = $this->request->post['exchange1c_price_type'];
        } else {
            $data['exchange1c_price_type'] = $this->config->get('exchange1c_price_type');
            if (empty($data['exchange1c_price_type'])) {
                $data['exchange1c_price_type'][] = [
                    'keyword'           => '',
                    'customer_group_id' => 0,
                    'quantity'          => 0,
                    'priority'          => 0,
                ];
            }
        }
        if (isset($this->request->post['exchange1c_root_category_is_catalog'])) {
            $data['exchange1c_root_category_is_catalog'] = $this->request->post['exchange1c_root_category_is_catalog'];
        } else {
            $data['exchange1c_root_category_is_catalog'] = $this->config->get('exchange1c_root_category_is_catalog');
        }

        if (isset($this->request->post['exchange1c_flush_product'])) {
            $data['exchange1c_flush_product'] = $this->request->post['exchange1c_flush_product'];
        } else {
            $data['exchange1c_flush_product'] = $this->config->get('exchange1c_flush_product');
        }

        if (isset($this->request->post['exchange1c_flush_category'])) {
            $data['exchange1c_flush_category'] = $this->request->post['exchange1c_flush_category'];
        } else {
            $data['exchange1c_flush_category'] = $this->config->get('exchange1c_flush_category');
        }

        if (isset($this->request->post['exchange1c_flush_manufacturer'])) {
            $data['exchange1c_flush_manufacturer'] = $this->request->post['exchange1c_flush_manufacturer'];
        } else {
            $data['exchange1c_flush_manufacturer'] = $this->config->get('exchange1c_flush_manufacturer');
        }

        if (isset($this->request->post['exchange1c_flush_quantity'])) {
            $data['exchange1c_flush_quantity'] = $this->request->post['exchange1c_flush_quantity'];
        } else {
            $data['exchange1c_flush_quantity'] = $this->config->get('exchange1c_flush_quantity');
        }

        if (isset($this->request->post['exchange1c_flush_attribute'])) {
            $data['exchange1c_flush_attribute'] = $this->request->post['exchange1c_flush_attribute'];
        } else {
            $data['exchange1c_flush_attribute'] = $this->config->get('exchange1c_flush_attribute');
        }

        if (isset($this->request->post['exchange1c_fill_parent_cats'])) {
            $data['exchange1c_fill_parent_cats'] = $this->request->post['exchange1c_fill_parent_cats'];
        } else {
            $data['exchange1c_fill_parent_cats'] = $this->config->get('exchange1c_fill_parent_cats');
        }

        if (isset($this->request->post['exchange1c_relatedoptions'])) {
            $data['exchange1c_relatedoptions'] = $this->request->post['exchange1c_relatedoptions'];
        } else {
            $data['exchange1c_relatedoptions'] = $this->config->get('exchange1c_relatedoptions');
        }

        if (isset($this->request->post['exchange1c_order_status_to_exchange'])) {
            $data['exchange1c_order_status_to_exchange'] = $this->request->post['exchange1c_order_status_to_exchange'];
        } else {
            $data['exchange1c_order_status_to_exchange'] = $this->config->get('exchange1c_order_status_to_exchange');
        }

        if (isset($this->request->post['exchange1c_dont_use_artsync'])) {
            $data['exchange1c_dont_use_artsync'] = $this->request->post['exchange1c_dont_use_artsync'];
        } else {
            $data['exchange1c_dont_use_artsync'] = $this->config->get('exchange1c_dont_use_artsync');
        }

        if (isset($this->request->post['exchange1c_seo_url'])) {
            $data['exchange1c_seo_url'] = $this->request->post['exchange1c_seo_url'];
        } else {
            $data['exchange1c_seo_url'] = $this->config->get('exchange1c_seo_url');
        }

        if (isset($this->request->post['exchange1c_full_log'])) {
            $data['exchange1c_full_log'] = $this->request->post['exchange1c_full_log'];
        } else {
            $data['exchange1c_full_log'] = $this->config->get('exchange1c_full_log');
        }

        if (isset($this->request->post['exchange1c_apply_watermark'])) {
            $data['exchange1c_apply_watermark'] = $this->request->post['exchange1c_apply_watermark'];
        } else {
            $data['exchange1c_apply_watermark'] = $this->config->get('exchange1c_apply_watermark');
        }

        if (isset($this->request->post['exchange1c_watermark'])) {
            $data['exchange1c_watermark'] = $this->request->post['exchange1c_watermark'];
        } else {
            $data['exchange1c_watermark'] = $this->config->get('exchange1c_watermark');
        }

        if (isset($data['exchange1c_watermark'])) {
            $data['thumb'] = $this->model_tool_image->resize($data['exchange1c_watermark'], 100, 100);
        } else {
            $data['thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
        }

        if (isset($this->request->post['exchange1c_order_status'])) {
            $data['exchange1c_order_status'] = $this->request->post['exchange1c_order_status'];
        } else {
            $data['exchange1c_order_status'] = $this->config->get('exchange1c_order_status');
        }

        if (isset($this->request->post['exchange1c_order_currency'])) {
            $data['exchange1c_order_currency'] = $this->request->post['exchange1c_order_currency'];
        } else {
            $data['exchange1c_order_currency'] = $this->config->get('exchange1c_order_currency');
        }

        if (isset($this->request->post['exchange1c_order_notify'])) {
            $data['exchange1c_order_notify'] = $this->request->post['exchange1c_order_notify'];
        } else {
            $data['exchange1c_order_notify'] = $this->config->get('exchange1c_order_notify');
        }
        if (isset($this->request->post['exchange1c_update_fields'])) {
            $data['exchange1c_update_fields'] = $this->request->post['exchange1c_update_fields'];
        } elseif ($this->config->get('exchange1c_update_fields')) {
            $data['exchange1c_update_fields'] = json_decode($this->config->get('exchange1c_update_fields'), true);
        } else {
            $data['exchange1c_update_fields'] = [""];
        }

        if (isset($this->request->post['exchange1c_update_desc_fields'])) {
            $data['exchange1c_update_desc_fields'] = $this->request->post['exchange1c_update_desc_fields'];
        } elseif ($this->config->get('exchange1c_update_desc_fields')) {
            $data['exchange1c_update_desc_fields'] = json_decode($this->config->get('exchange1c_update_desc_fields'), true);
        } else {
            $data['exchange1c_update_desc_fields'] = [""];
        }


        $data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

        $order_statuses = $this->model_localisation_order_status->getOrderStatuses();
        $data['order_statuses'] = $order_statuses;

        foreach ($order_statuses as $order_status) {
            $data['order_statuses'][] = [
                'order_status_id' => $order_status['order_status_id'],
                'name'            => $order_status['name'],
            ];
        }

        $product_fields = $this->model_extension_exchange1c->getColumnsName('product');
        $data['product_fields'] = [];

        foreach ($product_fields as $field) {
            $data['product_fields'][] = [
                'label' => $this->language->get('entry_' . $field),
                'name'  => $field,
            ];

        }
        $product_description_fields = $this->model_extension_exchange1c->getColumnsName('product_description');

        $data['product_description_fields'] = [];

        foreach ($product_description_fields as $field) {
            $data['product_description_fields'][] = [
                'label' => $this->language->get('entry_' . $field),
                'name'  => $field,
            ];

        }
        $data['product_fields'][] = [
            'label' => $this->language->get('entry_product_attribute'),
            'name'  => 'product_attribute',
        ];
        return $data;
    }

    private function loadAction($data)
    {
        $data['action'] = $this->url->link('extension/module/exchange1c', 'token=' . $this->session->data['token'], true);
        $data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true);
        return $data;
    }

    private function loadTemplate($data)
    {
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/exchange1c', $data));
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'extension/module/exchange1c')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    public function manualImport()
    {
        $this->load->language('extension/module/exchange1c');
        $json = [];

        // Check user has permission
        if (!$this->user->hasPermission('modify', 'extension/installer')) {
            $json['error'] = $this->language->get('error_permission');
        }
        $cache = DIR_CACHE . 'exchange1c/';

        if (!empty($this->request->files['file']['name'])) {

            $zip = new ZipArchive;


            if ($zip->open($this->request->files['file']['tmp_name']) === true) {
                $json['debug'] = '$zip->open true';
                $this->response->addHeader('Content-Type: application/json');
                $this->response->setOutput(json_encode($json));
                return;
                $this->modeCatalogInit(false);

                $zip->extractTo($cache);
                $files = scandir($cache);

                foreach ($files as $file) {
                    if (is_file($cache . $file)) {
                        $this->modeImport($file);
                    }
                }

                if (is_dir($cache . 'import_files')) {
                    $images = DIR_IMAGE . 'import_files/';

                    if (is_dir($images)) {
                        $this->cleanDir($images);
                    }

                    rename($cache . 'import_files/', $images);
                }

            } else {
                $json['debug'] = '$zip->open false';

                // Читаем первые 1024 байт и определяем файл по сигнатуре, ибо мало ли, какое у него имя
                $handle = fopen($this->request->files['file']['tmp_name'], 'r');
                $buffer = fread($handle, 1024);
                fclose($handle);

                if (strpos($buffer, '<Классификатор')) {
                    $json['debug'] .= "\n 'Классификатор'";
                    $this->modeCatalogInit(false);
                    move_uploaded_file($this->request->files['file']['tmp_name'], $cache . 'import.xml');
                    if ($this->modeImport('import.xml')) {

                    } else {
                        $json['error'] = $this->language->get('text_upload_error');
                        $this->response->addHeader('Content-Type: application/json');
                        $this->response->setOutput(json_encode($json));
                        return;
                    }

                } else if (strpos($buffer, '<ПакетПредложений')) {
                    $json['debug'] .= "\n 'ПакетПредложений'";
                    move_uploaded_file($this->request->files['file']['tmp_name'], $cache . 'offers.xml');
                    $this->modeImport('offers.xml');
                } else {
                    $json['error'] = $this->language->get('text_upload_error');
                    $this->response->addHeader('Content-Type: application/json');
                    $this->response->setOutput(json_encode($json));
                    return;
                }

            }

            $json['success'] = $this->language->get('text_upload_success');
        } else {
            $json['error'] = $this->language->get('error_upload');
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
            return;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
        return;
    }

    public function modeCatalogInit($echo = true)
    {
        $this->load->model('extension/exchange1c');
        $this->cleanCacheDir();
        // Проверяем естль ли БД для хранения промежуточных данных.
        $this->model_extension_exchange1c->checkDbSheme();
        // Очищаем таблицы
        $this->model_extension_exchange1c->flushDb([
                                                       'product'         => $this->config->get('exchange1c_flush_product'),
                                                       'category'        => $this->config->get('exchange1c_flush_category'),
                                                       'manufacturer'    => $this->config->get('exchange1c_flush_manufacturer'),
                                                       'attribute'       => $this->config->get('exchange1c_flush_attribute'),
                                                       'full_log'        => $this->config->get('exchange1c_full_log'),
                                                       'apply_watermark' => $this->config->get('exchange1c_apply_watermark'),
                                                       'quantity'        => $this->config->get('exchange1c_flush_quantity'),
                                                   ]);
        $limit = 100000 * 1024;
        if ($echo) {
            echo "zip=no\n";
            echo "file_limit=" . $limit . "\n";
        }
    }

    public function modeImport($manual = false)
    {

        $cache = DIR_CACHE . 'exchange1c/';

        if ($manual) {
            $filename = $manual;
//            $importFile = $cache . $filename;
        } else if (isset($this->request->get['filename'])) {
            $filename = $this->request->get['filename'];
//            $importFile = $cache . $filename;
        } else {
            $this->log->write('From: exchange1c/modeImport error file isset. ');
            return 0;
        }

        $this->load->model('extension/exchange1c');
        $this->load->model('localisation/language');
        // Определяем текущую локаль

        $language_info = $this->model_localisation_language->getLanguageByCode($this->config->get('config_language'));
        $language_id = $language_info['language_id'];

        if (strpos($filename, 'import') !== false) {
            $this->model_extension_exchange1c->parseImport($filename, $language_id);
            if ($this->config->get('exchange1c_fill_parent_cats')) {
                $this->model_extension_exchange1c->fillParentsCategories();
            }
            // Только если выбран способ deadcow_seo пока отключил
            if ($this->config->get('exchange1c_seo_url') == 1) {
                $this->load->model('module/deadcow_seo');
                $this->model_module_deadcow_seo->generateCategories($this->config->get('deadcow_seo_categories_template'), 'Russian');
                $this->model_module_deadcow_seo->generateProducts($this->config->get('deadcow_seo_products_template'), 'Russian');
                $this->model_module_deadcow_seo->generateManufacturers($this->config->get('deadcow_seo_manufacturers_template'), 'Russian');
            }

            if (!$manual) {
                echo "success\n";
            }

        } else if (strpos($filename, 'offers') !== false) {

            $exchange1c_price_type = $this->config->get('exchange1c_price_type');
            if (is_string($exchange1c_price_type)) {
                $exchange1c_price_type = json_decode($exchange1c_price_type, true);
            }
            $this->model_extension_exchange1c->parseOffers($filename, $exchange1c_price_type, $language_id);
            if (!$manual) {
                echo "success\n";
            }
        } else {
            echo "failure\n";
            echo $filename;
        }


        $this->cache->delete('product');
        return 1;
    }

    private function cleanCacheDir()
    {
        if (file_exists(DIR_CACHE . 'exchange1c')) {
            if (is_dir(DIR_CACHE . 'exchange1c')) {
                return $this->cleanDir(DIR_CACHE . 'exchange1c/');
            } else {
                unlink(DIR_CACHE . 'exchange1c');
            }
        }
        mkdir(DIR_CACHE . 'exchange1c');
        return 0;
    }

    private function cleanDir($root, $self = false)
    {
        $dir = dir($root);
        while ($file = $dir->read()) {
            if ($file == '.' || $file == '..') continue;
            if (file_exists($root . $file)) {
                if (is_file($root . $file)) {
                    unlink($root . $file);
                    continue;
                }
                if (is_dir($root . $file)) {
                    $this->cleanDir($root . $file . '/', true);
                    continue;
                }
            }
        }
        if ($self) {
            if (file_exists($root) && is_dir($root)) {
                rmdir($root);
                return 0;
            }
        }
        return 0;
    }


    public function modeCheckauth()
    {

        // Проверяем включен или нет модуль
        if (!$this->config->get('exchange1c_status')) {
            echo "failure\n";
            echo "1c module OFF\n";
            exit;
        }

        // Разрешен ли IP
        if ($this->config->get('exchange1c_allow_ip') != '') {
            $ip = $_SERVER['REMOTE_ADDR'];
            $allow_ips = explode("\r\n", $this->config->get('exchange1c_allow_ip'));

            if (!in_array($ip, $allow_ips)) {
                echo "failure\n";
                echo "IP is not allowed\n";
                exit;
            }
        }

        // Авторизуем
        if (($this->config->get('exchange1c_username') != '') && (@$_SERVER['PHP_AUTH_USER'] != $this->config->get('exchange1c_username'))) {
            echo "failure\n";
            echo "error login\n";
        }

        if (($this->config->get('exchange1c_password') != '') && (@$_SERVER['PHP_AUTH_PW'] != $this->config->get('exchange1c_password'))) {
            echo "failure\n";
            echo "error password\n";
            exit;
        }

        echo "success\n";
        echo "key\n";
        echo md5($this->config->get('exchange1c_password')) . "\n";
    }

    public function modeFile()
    {

        if (!isset($this->request->cookie['key'])) {
            return;
        }

        if ($this->request->cookie['key'] != md5($this->config->get('exchange1c_password'))) {
            echo "failure\n";
            echo "Session error";
            return;
        }

        $cache = DIR_CACHE . 'exchange1c/';

        // Проверяем на наличие имени файла
        if (isset($this->request->get['filename'])) {
            $uplod_file = $cache . $this->request->get['filename'];
        } else {
            echo "failure\n";
            echo "ERROR 10: No file name variable";
            return;
        }

        // Проверяем XML или изображения
        if (strpos($this->request->get['filename'], 'import_files') !== false) {
            $cache = DIR_IMAGE;
            $uplod_file = $cache . $this->request->get['filename'];
            $this->checkUploadFileTree(dirname($this->request->get['filename']), $cache);
        }

        // Получаем данные
        $data = file_get_contents("php://input");

        if ($data !== false) {
            if ($fp = fopen($uplod_file, "wb")) {
                $result = fwrite($fp, $data);
                if ($result === FALSE) {
                    echo "failure\n";
                    $this->log->write('failure fwrite($fp, $data)');
                } else {
                    echo "success\n";
                    chmod($uplod_file, 0777);
                }
            } else {
                echo "failure\n";
                echo "Can not open file: $uplod_file\n";
                echo $cache;
            }
        } else {
            echo "failure\n";
            echo "No data file\n";
        }


    }

    private function checkUploadFileTree($path, $curDir = null)
    {

        if (!$curDir) $curDir = DIR_CACHE . 'exchange1c/';

        foreach (explode('/', $path) as $name) {

            if (!$name) continue;

            if (file_exists($curDir . $name)) {
                if (is_dir($curDir . $name)) {
                    $curDir = $curDir . $name . '/';
                    continue;
                }

                unlink($curDir . $name);
            }

            mkdir($curDir . $name);
            $curDir = $curDir . $name . '/';
        }

    }


}
