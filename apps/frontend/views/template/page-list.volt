{#
<div class="page margin-top-60" id="" >
    <div class="page-list ">
        <div class="clearfix page-deatil">
            <a href="" class="fl page-prve gray"> &lt;上一页</a>
            <div class="page-middle fl">
                {% set pageCount = ( ceil(allFileCounter/4)==0 ? 1 : ceil(allFileCounter/4) ) %}
                <a class="active" href="">1</a>
                <a href="">2</a>
                <a href="">3</a>
                <a href="">4</a>
                <a href="">5</a>
            </div>
            <span class="fl ">...</span>
            <a href="" class="fl page-next">下一页 &gt; </a>
            <span class="fl">共{{ pageCount }}页</span>
            <label class="fl go-page">
                到<input type="text" value="1"  />页
            </label>
            <button class="fl">确认</button>
        </div>
    </div>
</div>#}
<nav>
    <ul class="pagination">
        <li>
            <a href="#" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>
        <li><a href="#">1</a></li>
        <li><a href="#">2</a></li>
        <li><a href="#">3</a></li>
        <li><a href="#">4</a></li>
        <li><a href="#">5</a></li>
        <li>
            <a href="#" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    </ul>
</nav>
