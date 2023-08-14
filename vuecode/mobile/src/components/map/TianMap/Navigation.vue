<script>
/**
 * @position
 *  TOP_LEFT 控件将定位到地图的左上角。
 *  TOP_RIGHT 控件将定位到地图的右上角。
 *  BOTTOM_LEFT 控件将定位到地图的左下角。
 *  BOTTOM_RIGHT 控件将定位到地图的右下角。
 */
import commonMixin from './mixins/common.js'
export default {
  mixins: [commonMixin('control')],
  props: {
    position: {
      type: String,
      default: 'TOP_LEFT'
    }
  },
  methods: {
    cssObjToText (cssObj) {
      var styleAry = []
      for (const key in cssObj) {
        styleAry.push(key + ':' + cssObj[key])
      }
      styleAry = styleAry.join(';')
      return styleAry
    },
    createButton ({ html, title, tag, container, cssObj, className }) {
      var link = document.createElement(tag)
      if (container) {
        container.appendChild(link)
      }
      if (html) {
        link.innerHTML = html
      }
      if (title) {
        link.title = title
      }
      if (className) {
        link.className = className
      }

      link.style = this.cssObjToText(cssObj)
      return link
    },
    load () {
      const { map, createButton, position } = this
      let pos = {
        // eslint-disable-next-line no-undef
        TOP_LEFT: T_ANCHOR_TOP_LEFT,
        // eslint-disable-next-line no-undef
        TOP_RIGHT: T_ANCHOR_TOP_RIGHT,
        // eslint-disable-next-line no-undef
        BOTTOM_LEFT: T_ANCHOR_BOTTOM_LEFT,
        // eslint-disable-next-line no-undef
        BOTTOM_RIGHT: T_ANCHOR_BOTTOM_RIGHT
      }
      // eslint-disable-next-line no-undef
      const newControl = new T.Control({ position: pos[position] })
      newControl.onAdd = function (map) {
        var container = document.createElement('div')
        container.className = 'Tian_stdMpCtrl'
        const zoomHeight = (102 / 18).toFixed(2)
        const currentZoom = map.getZoom()
        const height = currentZoom * zoomHeight
        // 移动按钮
        this.Tian_stdMpPan = createButton({
          html: '',
          title: '',
          tag: 'div',
          container: container,
          className: 'Tian_stdMpPan',
          cssObj: {
            width: '44px',
            height: '44px',
            overflow: 'hidden',
            background: `url(${require('../../../assets/images/map/mapctrls2d0.png')}) no-repeat`,
            position: 'absolute'
          }
        })
        this.Tian_panN = createButton({
          html: '',
          title: '向上平移',
          tag: 'div',
          className: 'Tian_panN',
          container: this.Tian_stdMpPan,
          cssObj: {
            width: '15px',
            height: '15px',
            left: '14px',
            top: '0',
            position: 'absolute'
          }
        })
        this.Tian_panW = createButton({
          html: '',
          title: '向左平移',
          tag: 'div',
          className: 'Tian_panW',
          container: this.Tian_stdMpPan,
          cssObj: {
            width: '15px',
            height: '15px',
            left: '1px',
            top: '12px',
            position: 'absolute'
          }
        })
        this.Tian_panE = createButton({
          html: '',
          className: 'Tian_panE',
          title: '向右平移',
          tag: 'div',
          container: this.Tian_stdMpPan,
          cssObj: {
            width: '15px',
            height: '15px',
            left: '27px',
            top: '12px',
            position: 'absolute'
          }
        })
        this.Tian_panS = createButton({
          html: '',
          className: 'Tian_panS',
          title: '向下平移',
          tag: 'div',
          container: this.Tian_stdMpPan,
          cssObj: {
            width: '15px',
            height: '15px',
            left: '14px',
            top: '25px',
            position: 'absolute'
          }
        })

        // 缩放按钮
        this.Tian_stdMpZoom = createButton({
          html: '',
          title: '',
          tag: 'div',
          className: 'Tian_stdMpZoom',
          container: container,
          cssObj: {
            height: '141px',
            width: '62px',
            top: '45px',
            overflow: 'hidden',
            position: 'absolute'
          }
        })

        this.Tian_stdMpZoomIn = createButton({
          html: '',
          title: '',
          className: 'Tian_stdMpZoomIn',
          tag: 'div',
          container: this.Tian_stdMpZoom,
          cssObj: {
            position: 'absolute',
            width: '22px',
            height: '21px',
            left: '12px',
            overflow: 'hidden',
            background: `url(${require('../../../assets/images/map/mapctrls2d0.png')}) no-repeat 0 -221px`,
            zIndex: 10
          }
        })

        this.Tian_stdMpZoomOut = createButton({
          html: '',
          title: '',
          tag: 'div',
          className: 'Tian_stdMpZoomOut',
          container: this.Tian_stdMpZoom,
          cssObj: {
            position: 'absolute',
            width: '22px',
            height: '21px',
            left: '12px',
            top: '120px',
            overflow: 'hidden',
            background: `url(${require('../../../assets/images/map/mapctrls2d0.png')}) no-repeat 0 -265px`,
            zIndex: 10
          }
        })

        this.Tian_stdMpSlider = createButton({
          html: '',
          title: '',
          tag: 'div',
          className: 'Tian_stdMpSlider',
          container: this.Tian_stdMpZoom,
          cssObj: {
            height: '102px',
            width: '37px',
            top: '18px',
            position: 'absolute'
          }
        })

        this.Tian_stdMpSliderBgTop = createButton({
          html: '',
          title: '',
          tag: 'div',
          className: 'Tian_stdMpSliderBgTop',
          container: this.Tian_stdMpSlider,
          cssObj: {
            height: '102px',
            width: '10px',
            left: '18px',
            overflow: 'hidden',
            position: 'absolute',
            background: `url(${require('../../../assets/images/map/mapctrls2d0.png')}) no-repeat -23px -226px`
          }
        })

        this.Tian_stdMpSliderBgBot = createButton({
          html: '',
          title: '',
          tag: 'div',
          className: 'Tian_stdMpSliderBgBot',
          container: this.Tian_stdMpSlider,
          cssObj: {
            height: `${height}px`,
            width: '10px',
            top: `${102 - height}px`,
            left: '19px',
            overflow: 'hidden',
            position: 'absolute',
            background: `url(${require('../../../assets/images/map/mapctrls2d0.png')}) no-repeat -33px bottom`
          }
        })

        this.Tian_stdMpSliderMask = createButton({
          html: '',
          title: '',
          tag: 'div',
          className: 'Tian_stdMpSliderMask',
          container: this.Tian_stdMpSlider,
          cssObj: {
            height: '100%',
            width: '24px',
            left: '10px',
            position: 'absolute',
            cursor: 'pointer'
          }
        })
        this.Tian_stdMpSliderBar = createButton({
          html: '',
          title: '',
          tag: 'div',
          className: 'Tian_stdMpSliderBar',
          container: this.Tian_stdMpSlider,
          cssObj: {
            height: '11px',
            width: '19px',
            top: `${102 - height}px`,
            left: '13px',
            overflow: 'hidden',
            position: 'absolute',
            background: `url(${require('../../../assets/images/map/mapctrls2d0.png')}) no-repeat 0 -309px`,
            zindex: '100'
          }
        })
        this.Tian_stdMpZoomIn.onclick = (e) => {
          e.stopPropagation()
          map.zoomIn()
        }
        this.Tian_stdMpZoomOut.onclick = (e) => {
          e.stopPropagation()
          map.zoomOut()
        }
        map.addEventListener('zoomend', () => {
          const zoom = map.getZoom()
          this.Tian_stdMpSliderBar.style.top = (102 - (zoom * zoomHeight)) + 'px'
          this.Tian_stdMpSliderBgBot.style.top = (102 - (zoom * zoomHeight)) + 'px'
          this.Tian_stdMpSliderBgBot.style.height = zoom * zoomHeight + 'px'
        })
        var moreStep = 50
        this.Tian_panN.onclick = () => {
          map.panBy({ x: 0, y: -moreStep })
        }
        this.Tian_panW.onclick = () => {
          map.panBy({ x: -moreStep, y: 0 })
        }
        this.Tian_panE.onclick = () => {
          map.panBy({ x: moreStep, y: 0 })
        }
        this.Tian_panS.onclick = () => {
          map.panBy({ x: 0, y: moreStep })
        }
        this.Tian_stdMpZoom.onclick = (e) => {
          e.stopPropagation()
        }
        this.Tian_stdMpSliderBar.onmousedown = (event) => {
          var e = event || window.event
          var y = e.clientY - this.Tian_stdMpSliderBar.offsetTop

          document.onmousemove = (event) => {
            var e = event || window.event
            map.disableDrag()
            var top = e.clientY - y
            if (top < 0) {
              top = 0
            } else if (top > this.Tian_stdMpSliderBgTop.offsetHeight - this.Tian_stdMpSliderBar.offsetHeight) {
              top = this.Tian_stdMpSliderBgTop.offsetHeight - this.Tian_stdMpSliderBar.offsetHeight
            }
            this.Tian_stdMpSliderBar.style.top = top + 'px'
            this.Tian_stdMpSliderBgBot.style.top = top + 'px'
            this.Tian_stdMpSliderBgBot.style.height = this.Tian_stdMpSliderBgTop.offsetHeight - top + 'px'
          }
          document.onmouseup = () => {
            map.enableDrag()
            var height = parseInt(this.Tian_stdMpSliderBgBot.style.height)
            var zoom = parseInt(height / zoomHeight)
            map.setZoom(zoom)
            document.onmousemove = null
            document.onmouseup = null
          }
        }

        return container
      }
      map.addControl(newControl)
    }
  },
  // eslint-disable-next-line vue/require-render-return
  render () {}
}
</script>
<style lang="scss" scoped>
</style>
