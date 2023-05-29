<!-- jQuery -->
<script src="<?php echo base_url; ?>plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="<?php echo base_url; ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>


<!-- DataTables  & Plugins -->
<script src="<?php echo base_url; ?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url; ?>plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo base_url; ?>plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo base_url; ?>plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?php echo base_url; ?>plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url; ?>plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="<?php echo base_url; ?>plugins/jszip/jszip.min.js"></script>
<script src="<?php echo base_url; ?>plugins/pdfmake/pdfmake.min.js"></script>
<script src="<?php echo base_url; ?>plugins/pdfmake/vfs_fonts.js"></script>
<script src="<?php echo base_url; ?>plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="<?php echo base_url; ?>plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="<?php echo base_url; ?>plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

<!-- AdminLTE<?php echo base_url; ?> -->
<script src="<?php echo base_url; ?>dist/js/adminlte.js"></script>

<!-- OPTIONAL<?php echo base_url; ?> SCRIPTS -->
<script src="<?php echo base_url; ?>plugins/chart.js/Chart.min.js"></script>

<!-- AdminLTE<?php echo base_url; ?> for demo purposes -->
<!-- <script <?php echo base_url; ?>src="dist/js/demo.js"></script> -->

<!-- AdminLTE<?php echo base_url; ?> dashboard demo (This is only for demo purposes) -->
<script src="<?php echo base_url; ?>dist/js/pages/dashboard3.js"></script>

<!-- SweetAlert2 -->
<script src="<?php echo base_url ?>plugins/sweetalert2/sweetalert2.min.js"></script>

<!-- Toastr -->
<script src="<?php echo base_url ?>plugins/toastr/toastr.min.js"></script>

<!-- select2 -->
<script src="<?php echo base_url; ?>plugins/select2/js/select2.min.js"></script>

<!-- TableCheckAll -->
<script src="<?php echo base_url; ?>plugins/TableCheckAll/TableCheckAll.js"></script>

 
<!-- datatables-checkboxes -->
<script src="<?php echo base_url; ?>plugins/datatables-checkboxes/js/dataTables.checkboxes.min.js"></script>

<script>
  $(function () {

  var ticksStyle = {
    fontColor: '#495057',
    fontStyle: 'bold'
  }

  var mode = 'index'
  var intersect = true

    var $visitorsChart = $('#sales-demand-chart')
    var visitorsChart = new Chart($visitorsChart, {
      data: {
        // labels: ['18th', '20th', '22nd', '24th', '26th', '28th', '30th'],
        labels: [
          <?php
            $query = "SELECT YEAR(`datetime_added`) AS `year` , 
                          DATE_FORMAT(`datetime_added`, '%b') AS `month_name` , 
                              SUM(`quantity`) AS `total_quantity` 
                      FROM sales_transactions_2_items  
                      WHERE YEAR(`datetime_added`) = $current_year 
                      GROUP BY YEAR(`datetime_added`), MONTH(`datetime_added`) 
                      ORDER BY MONTH(`datetime_added`), `datetime_added` ASC;";
            $rows = query($query);
            if($rows)
            {
              foreach ($rows as $key => $row) {
          ?>
                '<?php echo $row['month_name']; ?>',
          <?php
              }
            }
          ?>
        ],
        datasets: [{
            type: 'line',
            // data: [100, 120, 170, 167, 180, 177, 160],
            label: 'Demand',
            data: [
                    <?php
                      $query = "SELECT YEAR(`datetime_added`) AS `year` , 
                                    DATE_FORMAT(`datetime_added`, '%b') AS `month_name` , 
                                        SUM(`quantity`) AS `total_quantity` 
                                FROM sales_transactions_2_items  
                                WHERE YEAR(`datetime_added`) = $current_year 
                                GROUP BY YEAR(`datetime_added`), MONTH(`datetime_added`) 
                                ORDER BY MONTH(`datetime_added`), `datetime_added` ASC;";
                      $rows = query($query);
                      if($rows)
                      {
                        foreach ($rows as $key => $row) {
                    ?>
                          '<?php echo $row['total_quantity']; ?>',
                    <?php
                        }
                      }
                    ?>
                  ],
            backgroundColor: 'transparent',
            borderColor: '#007bff',
            pointBorderColor: '#007bff',
            pointBackgroundColor: '#007bff',
            fill: false
            // pointHoverBackgroundColor: '#007bff',
            // pointHoverBorderColor    : '#007bff'
          },
        ]
      },
      options: {
        maintainAspectRatio: false,
        tooltips: {
          mode: mode,
          intersect: intersect
        },
        hover: {
          mode: mode,
          intersect: intersect
        },
        legend: {
          display: false
        },
        scales: {
          yAxes: [{
            // display: false,
            gridLines: {
              display: true,
              lineWidth: '4px',
              color: 'rgba(0, 0, 0, .2)',
              zeroLineColor: 'transparent'
            },
            ticks: $.extend({
              beginAtZero: true,
              suggestedMax: 200
            }, ticksStyle)
          }],
          xAxes: [{
            display: true,
            gridLines: {
              display: false
            },
            ticks: ticksStyle
          }]
        }
      }
    });


  var $salesChart = $('#sales-amount-chart')
  var salesChart = new Chart($salesChart, {
      type: 'bar',
      data: {
        labels: [
            <?php
              $query_h = "SELECT YEAR(`datetime_checkedout`) AS `year`, DATE_FORMAT(`datetime_checkedout`, '%b') AS `month_name`
                            , SUM(`total_amount`) AS total_amount
                        FROM sales_transactions_2
                        WHERE `status` IN (0,1) AND YEAR(`datetime_checkedout`) = $current_year
                        GROUP BY YEAR(`datetime_checkedout`), DATE_FORMAT(`datetime_checkedout`, '%b')
                        ORDER BY `datetime_checkedout` ASC";
              $rows_h = query($query_h);
              if($rows_h)
              {
                foreach ($rows_h as $key => $row_h) {
            ?>
                  '<?php echo $row_h['month_name']; ?>',
            <?php
                }
              }
            ?>
        ],
        datasets: [
          {
            backgroundColor: '#007bff',
            borderColor: '#007bff',
            data: [
              <?php
                $query = "SELECT YEAR(`datetime_checkedout`) AS `year`, DATE_FORMAT(`datetime_checkedout`, '%b') AS `month_name`
                              , SUM(`total_amount`) AS total_amount
                          FROM sales_transactions_2
                          WHERE `status` IN (0,1) AND YEAR(`datetime_checkedout`) = $current_year
                          GROUP BY YEAR(`datetime_checkedout`), DATE_FORMAT(`datetime_checkedout`, '%b')
                          ORDER BY `datetime_checkedout` ASC";
                $rows = query($query);
                if($rows)
                {
                  foreach ($rows as $key => $row) {
              ?>
                    '<?php echo $row['total_amount']; ?>',
              <?php
                  }
                }
              ?>
            ]
          },
          // {
          //   backgroundColor: '#ced4da',
          //   borderColor: '#ced4da',
          //   data: [700, 1700, 2700, 2000, 1800, 1500, 2000]
          // }
        ]
      },
      options: {
        maintainAspectRatio: false,
        // tooltips: {
        //   mode: mode,
        //   intersect: intersect,
        // },
        // tooltips: {
        //   callbacks: {
        //     label: function(tooltipItem, data) {
        //       var dataLabel = data.labels[tooltipItem.index];
        //       var value = ': ' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index].toLocaleString();
        //       if (Chart.helpers.isArray(dataLabel)) {
        //         dataLabel = dataLabel.slice();
        //         dataLabel[0] += value;
        //       } else {
        //         dataLabel += value;
        //       }
        //       return dataLabel;
        //     }
        //   },
        // },
        tooltips: {
              callbacks: {
                  label: function(tooltipItem, data) {
                      // return tooltipItem.yLabel.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                      return number_format(tooltipItem.yLabel.toFixed());
                  }
              }
          },
        hover: {
          mode: mode,
          intersect: intersect
        },
        legend: {
          display: false
        },
        locale: 'en-US',
        scales: {
          yAxes: [{
            // display: false,
            gridLines: {
              display: true,
              lineWidth: '4px',
              color: 'rgba(0, 0, 0, .2)',
              zeroLineColor: 'transparent'
            },
            ticks: $.extend({
              beginAtZero: true,

              // Include a dollar sign in the ticks
              callback: function (value) {
                          if (value >= 1000000) {
                            value /= 1000000
                            value += 'm'
                          } else if( value >= 1000) {
                            value /= 1000
                            value += 'k'
                          }

                          return 'P' + value
                        }
            }, ticksStyle)
          }],
          xAxes: [{
            display: true,
            gridLines: {
              display: false
            },
            ticks: ticksStyle
          }]
        },
      },
    });

    // setIntl('en-US');

    // const ctx = document.getElementById('sales-amount-chart').getContext('2d');
    // const chart = new Chart(ctx, options)
    // const setIntl = (intl) => {
    //   chart.options.locale = intl;
    //   chart.update();
    // }

    function number_format(number, decimals, dec_point, thousands_sep) {
      // *     example: number_format(1234.56, 2, ',', ' ');
      // *     return: '1 234,56'
          number = (number + '').replace(',', '').replace(' ', '');
          var n = !isFinite(+number) ? 0 : +number,
                  prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                  sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                  dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                  s = '',
                  toFixedFix = function (n, prec) {
                      var k = Math.pow(10, prec);
                      return '' + Math.round(n * k) / k;
                  };
          // Fix for IE parseFloat(0.55).toFixed(0) = 0;
          s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
          if (s[0].length > 3) {
              s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
          }
          if ((s[1] || '').length < prec) {
              s[1] = s[1] || '';
              s[1] += new Array(prec - s[1].length + 1).join('0');
          }
          return s.join(dec);
      }


      $("#sales-product-month").DataTable({
        "searching": true,
        "lengthChange": false,
        "responsive": true, 
        "lengthChange": false, 
        "autoWidth": false,
      });

      // $("#sales-product-month_filter").addClass("form-inline float-right");
      // $("#sales-product-month_wrapper div.row:eq(0)").addClass("pb-2");
      // $("#sales-product-month_wrapper > div.row > div:eq(0)").removeClass('col-sm-12 col-md-6');
      // $("#sales-product-month_wrapper > div.row > div:eq(1)").removeClass('col-md-6').addClass('col-md-12');
      // .buttons().container().appendTo('#sales-product-month_wrapper .col-md-6:eq(0)');
  });
</script>

