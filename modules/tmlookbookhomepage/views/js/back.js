var tmlbhp = {
  ajax : function() {
    this.init = function(options) {
      this.options = $.extend({}, this.defaults, options || {});
      this.request();
      return this;
    };
    this.defaults = {
      type : 'POST',
      url : tmmlhp_theme_url+'&ajax',
      headers : {"cache-control" : "no-cache"},
      cache : false,
      dataType : "json",
      async : false,
      success : function() {
      }
    };
    this.request = function() {
      $.ajax(this.options);
    };
  }
};

$(document).ready(function(){
  var $d =$(this),
    $blocks = $('table.hookTopColumn  tbody, table.hookHome  tbody');

  if ($blocks.length) {
    $('tr td.dragHandle', $blocks).wrapInner('<div class="positions"/>');
    $('tr td.dragHandle', $blocks).wrapInner('<div class="dragGroup"/>');
    $('tr', $blocks).each(function() {
      var id = $(this).find('td:first').text();
      $(this).attr('id', 'item_' + id.trim());
    });

    $blocks.sortable({
      cursor : 'move',
      handle : '.dragHandle',
      update : function(event, ui) {
        $('>tr', event.currentTarget).each(function(index) {
          $(this).find('.positions').text(index + 1);
        });
      }
    }).bind('sortupdate', function() {
      var orders = $(this).sortable('toArray');
      var options = {
        data : {
          action : 'updateblocksposition',
          item : orders,
        },
        success : function(msg) {
          if (msg.error) {
            showErrorMessage(msg.error);
            return;
          }
          showSuccessMessage(msg.success);
        }
      };
      var ajax = new tmlbhp.ajax();
      ajax.init(options);
    });
  }
});