import OrderNew from "./modules/Order/new";

class Form {
    static init() {
        new OrderNew()
    }
}

jQuery(document).ready(function () {
   Form.init();
});
