import OrderNew from "./modules/Order/new";
import modifier from "./formModifier";

class Form {
    static init() {
        new OrderNew()
        modifier('#shop_options_paypalPayment')
    }
}

jQuery(document).ready(function () {
   Form.init();
});
