import Selectize from "selectize";

Selectize.define('option-loader', function () {
    let self = this;
    let select = self.$input[0];
    let cleanTarget = function (target) {
        target.selectize.clear(true);
        target.selectize.clearOptions();
        target.selectize.refreshOptions();
        target.selectize.refreshItems();
        target.selectize.clearCache();
        target.selectize.disable();

        if (target.hasAttribute('data-option-loader-target')) {
            cleanTarget(target.optionLoaderTarget);
        }

    };

    this.on('initialize', function () {

        self.on('change', function () {
            if (select.optionLoaderTarget) {

                cleanTarget(select.optionLoaderTarget);

                select.optionLoader.loadProgrammatically(select).then(resolved => {
                    resolved.target.selectize.clear(true);
                    resolved.target.selectize.clearOptions();
                    resolved.target.selectize.refreshOptions();
                    resolved.target.selectize.refreshItems();
                    resolved.target.selectize.clearCache();

                    resolved.data.forEach(option => {
                        resolved.target.selectize.addOption({
                            text: option.bn_name,
                            value: option.id
                        });
                    });
                    resolved.target.selectize.enable();
                });
            }
        });
    });
});