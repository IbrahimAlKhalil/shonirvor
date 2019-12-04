import {Repeater} from '../../../modules/repeater'

document.addEventListener('DOMContentLoaded', () => {

    $('[data-repeater-clone]').each(function () {
        let card = $(this)
        card.find('.remove-btn').on('click', function (event) {
            event.preventDefault()
            $(card).fadeOut('slow', function () {
                $(card).remove()
            })
        })
    })

    let container = document.getElementById('sub-category-parent')

    fetch(container.getAttribute('data-route')).then(response => response.json()).then(workMethods => {


        let moSubReq = document.getElementById('mo-sub-category-request')
        let moNoSub = document.getElementById('mo-no-sub-category')

        moNoSub.addEventListener('change', function () {
            $(moSubReq).toggleClass('d-none')
        })

        /*******************************  Sub category  ********************************/

        function subCategory(container, select) {
            let repeater = new Repeater(container, function (id, count) {
                let cardBody = ''

                workMethods.forEach((workMethod, MethodCount) => {
                    if (workMethod.id === 4) {
                        cardBody += `
                        <div class="row mt-2">
                            <div class="col-md-8">
                                <label for="work-method-${workMethod.id}-${count}" class="checkbox">${workMethod.name}
            <input type="checkbox" id="work-method-${workMethod.id}-${count}" name="sub-category-rates[${count}][work-methods][${MethodCount}][checkbox]">
                <span></span>
                </label>
                <input type="hidden" name="sub-category-rates[${count}][id]" value="${id}">
                <input type="hidden" name="sub-category-rates[${count}][work-methods][${MethodCount}][id]" value="${workMethod.id}">
                </div>
                </div>`
                        return
                    }

                    cardBody += `
                        <div class="row mt-2">
                            <div class="col-md-8">
                                <label for="work-method-${workMethod.id}-${count}" class="checkbox">${workMethod.name}
                                    <input type="checkbox" id="work-method-${workMethod.id}-${count}" name="sub-category-rates[${count}][work-methods][${MethodCount}][checkbox]">
                                    <span></span>
                                </label>
                                <input type="hidden" name="sub-category-rates[${count}][id]" value="${id}">
                            </div>
                            <div class="col">
                                <input type="number" class="form-control" placeholder="রেট" name="sub-category-rates[${count}][work-methods][${MethodCount}][rate]">
                                <input type="hidden" name="sub-category-rates[${count}][work-methods][${MethodCount}][id]" value="${workMethod.id}">
                            </div>
                        </div>`
                })


                let fragment = document.createElement('div')
                fragment.innerHTML =
                    `<div class="card mt-2">
                    <div class="card-header pb-0 pt-2">${select.querySelector(`[value='${id}']`).innerHTML}</div>
                    <div class="card-body">
                          ${cardBody}
                    </div>
                </div>`
                return fragment.firstElementChild.cloneNode(true)
            })

            select.selectize.on('change', value => {
                repeater.removeAll()
                if (!value) return
                repeater.repeat([value, 0])
            })
        }

        subCategory(document.getElementById('sub-category-parent'), document.getElementById('sub-categories'))
        subCategory(document.getElementById('mo-sub-category-parent'), document.getElementById('mo-sub-categories'))
    })
})
