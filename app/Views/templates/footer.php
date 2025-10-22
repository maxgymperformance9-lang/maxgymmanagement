<footer class="footer">
   <div class="container-fluid">
      <nav class="float-left">
         <ul>
            <li>
               <a href="">
                  
               </a>
            </li>
         </ul>
      </nav>
      <div class="copyright float-right">
         <?= is_array($generalSettings) ? $generalSettings['copyright'] : $generalSettings->copyright; ?>
      </div>
   </div>
</footer>