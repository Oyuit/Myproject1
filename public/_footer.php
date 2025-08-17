<?php // public/_footer.php ?>
</div>
<footer class="border-top border-secondary py-4 mt-5">
  <div class="container small d-flex flex-wrap justify-content-between align-items-center gap-2">
    <div>© <?= date('Y') ?> Akkrasin 87. All rights reserved.</div>
    <div class="text-muted">พัฒนาโดย PHP + Bootstrap</div>
  </div>

  <?php 
  // แสดงแผนที่เฉพาะหน้า index.php เท่านั้น
  if (basename($_SERVER['SCRIPT_NAME']) === 'index.php'): ?>
    <!-- แผนที่ร้าน -->
    <div class="container mt-3">
      <h6 class="mb-2">📍 ที่ตั้งร้าน</h6>
      <div class="ratio ratio-16x9">
        <iframe 
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d62042.93314598249!2d102.1975835!3d15.1324381!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3119377c76b66125%3A0x43924006f590ac89!2z4Lin4Lix4LiU4LmA4Lil4Liy4LiZ4LiB4Liy4LijIDg3!5e0!3m2!1sth!2sth!4v1722320000000!5m2!1sth!2sth" 
          width="600" height="450" 
          style="border:0;" 
          allowfullscreen="" 
          loading="lazy" 
          referrerpolicy="no-referrer-when-downgrade">
        </iframe>
      </div>
    </div>
  <?php endif; ?>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
