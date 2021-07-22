/**
 * @Author: Amirhosseinhpv
 * @Date:   2021/07/08 10:01:18
 * @Email:  its@hpv.im
 * @Last modified by:   Amirhosseinhpv
 * @Last modified time: 2021/07/08 18:33:14
 * @License: GPLv2
 * @Copyright: Copyright © Amirhosseinhpv (https://hpv.im), all rights reserved.
 */

(function ($) {
  vc.atts.pepro_mediapicker = {
    init: function (param, $field) {
      $(".vc_filepicker .wpb_element_label").each(function (index) {
        $(this).append(
          `<a class='vc_filepicker_btn' href='javascript:;' style="padding: 0 0.5rem; margin: .4rem;box-shadow: none !important;"><i class='fas fa-folder-open'></i></a>`
        );
      });
      $(".vc_filepicker .vc_filepicker_btn").click(function (e) {
        e.preventDefault();
        var me = $(this);
        var upload = wp
          .media({ multiple: false })
          .on("select", function () {
            var select = upload.state().get("selection");
            var attach = select.first().toJSON();
            me.parents(".vc_filepicker")
              .find(".textfield")
              .val(attach.url)
              .trigger("change");
          })
          .open();
      });
      $(document).on("click tap",".wpb_el_type_textfield .wpb_element_label", function (e) {
          $(this).parent().find(".textfield").focus();
        } );
      pepro_mediapicker_generate();
      $(document).on("click tap change", ".vc_active[data-vc-shortcode='video2'] :input", function(e){
        pepro_mediapicker_generate();
      });
      function pepro_mediapicker_generate() {
        $src = $(".textfield.src").val();
        $webm = $(".textfield.webm").val();
        $width = $(".textfield.width").val();
        $height = $(".textfield.height").val();
        $poster = $(".attach_image.poster").val();
        $playicon = $(".attach_image.playicon").val();
        $loop = $(".checkbox.loop").prop("checked") ? "true" : "false";
        $autoplay = $(".checkbox.autoplay").prop("checked") ? "true" : "false";
        $class = $(".textfield.class").val();
        $border = $(".textfield.border").val();
        $play_w = $(".textfield.play_w").val();
        $play_h = $(".textfield.play_h").val();
        $(".wpb_el_type_pepro_mediapicker").html(
          `[video2 width="${$width}" height="${$height}" poster="${$poster}" loop="${$loop}" autoplay="${$autoplay}" class="${$class}" border="${$border}" src="${$src}" webm="${$webm}" playicon="${$playicon}" play_w="${$play_w}" play_h="${$play_h}"]`
        );
      }
    },

  };
})(window.jQuery);
