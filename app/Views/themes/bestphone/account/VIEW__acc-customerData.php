
<form id="form-customerData" class="js-validate table-form" data-route="<?= route_to('Account-changeCustomerData') ?>" method="post">
    <table class="table table-hover table-sm" id="myTable">
        <tbody>
            <tr>
                <td class="align-middle text-left">Име <b class="text-danger">*</b></td>
                <td>
                    <div class="js-form-message js-focus-state input-group"> 
                        <input class="form-control" 
                               name="klient_name" 
                               data-msg="Задълтелно поле"
                               data-error-class="u-has-error"
                               data-success-class="u-has-success"
                               type="text"
                               value="<?= $customerData -> klient_name ?? '' ?>"  
                               required>
                    </div>
                </td>
            </tr>

            <tr>
                <td class="align-middle text-left">Булстат/ЕГН</td>
                <td>
                    <div class="js-form-message js-focus-state input-group"> 
                        <input class="form-control"
                               name="bulstat" 
                               data-error-class="u-has-error"
                               data-success-class="u-has-success"
                               type="text" 
                               value="<?= $customerData -> bulstat ?? '' ?>">
                    </div>
                </td>
            </tr>

            <tr>
                <td class="align-middle text-left">Мол</td>
                <td><input name="klient_mol" type="text" class="form-control" placeholder="" value="<?= $customerData -> klient_mol ?? '' ?>"></td>
            </tr>

            <tr>
                <td class="align-middle text-left">Пощ. код</td>
                <td><input name="klient_postCode" type="text" class="form-control" placeholder="" value="<?= $customerData -> klient_postCode ?? '' ?>"></td>
            </tr>

            <tr>
                <td class="align-middle text-left">Град</td>
                <td>
                    <div class="js-form-message js-focus-state input-group"> 
                        <input class="form-control" 
                               name="klient_grad" 
                               data-error-class="u-has-error"
                               data-success-class="u-has-success"
                               type="text" 
                               value="<?= $customerData -> klient_grad ?? '' ?>">
                    </div>
                </td>
            </tr>

            <tr>
                <td class="align-middle text-left">Адрес</td>
                <td><input name="klient_adres" type="text" class="form-control" placeholder="" value="<?= $customerData -> klient_adres ?? '' ?>"></td>
            </tr>

            <tr>
                <td class="align-middle text-left">Email <b class="text-danger">*</b></td>
                <td>
                    <input class="form-control" 
                           name="email" 
                           data-msg="Задълтелно поле"
                           data-error-class="u-has-error"
                           data-success-class="u-has-success"
                           type="email" 
                           value="<?= $customerData -> email ?? '' ?>"
                           required>
                </td>
            </tr>

            <tr>
                <td class="align-middle text-left">Телефон <b class="text-danger">*</b></td>
                <td>
                    <input class="form-control" 
                           name="klient_tel" 
                           data-msg="Задължително поле"
                           data-error-class="u-has-error"
                           data-success-class="u-has-success"
                           type="text" 
                           value="<?= $customerData -> klient_tel ?? '' ?>"
                           required>
                </td>
            </tr>
        </tbody>
    </table>
</form>

<button class="btn btn-primary-orange w-100 rounded-0" form="form-customerData" type="submit">Запис</button>
