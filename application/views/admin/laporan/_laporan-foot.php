  <p class="dicetak">
    Dicetak: <?php echo tgl_indo(date('Y-m-d')) . ' ' . date('H:i'); ?> oleh <?php echo html_escape($this->session->userdata('username')); ?>
  </p>

  <div class="tombol noPrint">
    <input type="button" onclick="window.print()" value="Cetak Halaman">
    <input type="button" onclick="window.close()" value="Tutup">
  </div>

</div>
</body>
</html>
