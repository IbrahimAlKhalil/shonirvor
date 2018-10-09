// jQuery Flexdatalist CSS
import '../../plugins/jquery-flexdatalist/jquery.flexdatalist.css';

// jQuery Flexdatalist
import '../../plugins/jquery-flexdatalist/jquery.flexdatalist';


$(document).ready(function () {

    // Sub Category Search
    $('#searchCategoryInput').flexdatalist({
        url: 'api/search',
        minLength: 1,
        searchContain: true,
        focusFirstResult: true,
        selectionRequired: true,
        searchIn: 'name',
        valueProperty: 'id',
        keywordParamName: 'sub-category',
        textProperty: '{name}',
        groupBy: 'category_name',
        noResultsText: 'কোন সার্ভিস পাওয়া যায়নি।'
    });

});