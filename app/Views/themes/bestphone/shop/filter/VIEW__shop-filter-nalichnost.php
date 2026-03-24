<ul class="w-100 treeview p-0 m-0">
    <li>
        <b>В наличност</b>
    </li>  

    <li>
        <ul class="feature-block filters child border-top2 banner-bg mx-3">
            <li>     
                <a class="text-black">
                    <input id="js-nalichChk" 
                           class="js-productFilter" 
                           type="checkbox" 
                           value="1"
                           <?= isset($_GET['f_instock']) && !empty($_GET['f_instock']) ? 'checked' : '' ?>>&nbsp;Да
                </a>
            </li>   
        </ul> 
    </li>   
</ul>

