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
  name: 'TianGeolocation',
  props: {
    position: {
      type: String,
      default: 'TOP_LEFT'
    },
    type: {
      type: String,
      default: 'lo'
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
      const { map, createButton, position, type } = this
      const that = this
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
      newControl.onAdd = function () {
        var container = document.createElement('div')
        this.Tian_geolocationContainer = createButton({
          html: '',
          title: '',
          tag: 'div',
          container: container,
          className: 'Tian_geolocationContainer',
          cssObj: {
            background: `url(${require('../../../assets/images/map/gradient-bg-1x64.png')}) repeat-x`,
            'background-size': '1px 32px',
            overflow: 'hidden',
            border: '1px solid #d9d7d5',
            'border-radius': '3px',
            'box-shadow': '1px 1px 1px rgb(0 0 0 / 20%)'
          }
        })

        this.Tian_geolocationIconBackground = createButton({
          html: '',
          title: '',
          tag: 'div',
          container: this.Tian_geolocationContainer,
          className: 'Tian_geolocationIconBackground',
          cssObj: {
            float: 'left',
            width: '32px',
            height: '32px',
            cursor: 'pointer',
            background: `url(${require('../../../assets/images/map/default-40x40.png')}) no-repeat`,
            'background-size': '20px 20px',
            'background-position': 'center center'

          }
        })

        this.Tian_geolocationAddress = createButton({
          html: '',
          title: '',
          tag: 'div',
          container: this.Tian_geolocationContainer,
          className: 'Tian_geolocationAddress',
          cssObj: {
            float: 'left',
            cursor: 'pointer',
            background: `url(${require('../../../assets/images/map/gradient-bg-1x64.png')}) repeat-x`,
            'background-size': '1px 32px',
            'min-width': '70px',
            'padding-left': '10px',
            'padding-right': '10px',
            'border-left': '1px solid #d9d7d5',
            'display': 'none'
          }
        })
        this.Tian_geolocationAddressText = createButton({
          html: '',
          title: '',
          tag: 'div',
          container: this.Tian_geolocationAddress,
          className: 'Tian_geolocationAddressText',
          cssObj: {
            height: '32px',
            display: 'table-cell',
            'vertical-align': 'middle'
          }
        })
        this.Tian_geolocationIconBackground.onclick = (e) => {
          e.stopPropagation()
          // eslint-disable-next-line no-undef
          var lo = new T.Geolocation()
          // eslint-disable-next-line no-undef
          var lc = new T.LocalCity()
          // eslint-disable-next-line no-undef
          var geocoder = new T.Geocoder()

          this.Tian_geolocationIconBackground.style.background = `url(${require('../../../assets/images/map/loading-40x40.gif')}) no-repeat`
          this.Tian_geolocationIconBackground.style.backgroundSize = `20px 20px`
          this.Tian_geolocationIconBackground.style.backgroundPosition = `center center`
          if (type == 'lc') {
            lc.location((e) => {
              // 经纬度解析
              geocoder.getLocation(e.lnglat, (data) => {
                const {result: {result: {addressComponent: {city, county}}}} = data
                const cityText = city + county
                this.Tian_geolocationAddressText.innerText = cityText
                this.Tian_geolocationAddress.style.display = 'block'
                this.Tian_geolocationIconBackground.style.background = `url(${require('../../../assets/images/map/default-40x40.png')}) no-repeat`
                this.Tian_geolocationIconBackground.style.backgroundSize = `20px 20px`
                this.Tian_geolocationIconBackground.style.backgroundPosition = `center center`
                that.$emit('geolocation', {geocoder: data, lnglat: e})
              })
            })
          } else {
            lo.getCurrentPosition((e) => {
              if (lo.getStatus() == 3) {
                this.Tian_geolocationAddress.style.display = 'block'
                this.Tian_geolocationAddressText.innerText = '获取定位失败'
                this.Tian_geolocationIconBackground.style.background = `url(${require('../../../assets/images/map/default-40x40.png')}) no-repeat`
                this.Tian_geolocationIconBackground.style.backgroundSize = `20px 20px`
                this.Tian_geolocationIconBackground.style.backgroundPosition = `center center`
                that.$emit('positionError')
                return
              }

              geocoder.getLocation(e.lnglat, (data) => {
                if (lo.getStatus() == 0) {
                  const {result: {result: {addressComponent: {city, county}}}} = data
                  const cityText = city + county
                  this.Tian_geolocationAddress.style.display = 'block'
                  this.Tian_geolocationAddressText.innerText = cityText
                  this.Tian_geolocationIconBackground.style.background = `url(${require('../../../assets/images/map/default-40x40.png')}) no-repeat`
                  this.Tian_geolocationIconBackground.style.backgroundSize = `20px 20px`
                  this.Tian_geolocationIconBackground.style.backgroundPosition = `center center`
                  that.$emit('positionSuccess', {geocoder: data, lnglat: e})
                } else {
                  this.Tian_geolocationAddress.style.display = 'block'
                  this.Tian_geolocationAddressText.innerText = '获取定位失败'
                  this.Tian_geolocationIconBackground.style.background = `url(${require('../../../assets/images/map/default-40x40.png')}) no-repeat`
                  this.Tian_geolocationIconBackground.style.backgroundSize = `20px 20px`
                  this.Tian_geolocationIconBackground.style.backgroundPosition = `center center`
                  that.$emit('positionError')
                }
              })
            })
          }
        }
        return container
      }
      map.addControl(newControl)
      this.$emit('geolocationReady', newControl)
    }
  },
  // eslint-disable-next-line vue/require-render-return
  render () {}
}
</script>
