<script>
export default {
  name: 'MenuItem',
  functional: true,
  props: {
    icon: {
      type: String,
      default: ''
    },
    title: {
      type: String,
      default: ''
    },
    tag: {
      type: String,
      default: ''
    }
  },
  render(h, context) {
    const { icon, title, tag } = context.props
    const vnodes = []

    if (icon) {
      vnodes.push(<svg-icon icon-class={icon}/>)
    }

    if (title) {
      if (tag) {
        vnodes.push(<span slot='title' data-tag={tag}>{(title)}</span>)
      } else {
        vnodes.push(<span slot='title'>{(title)}</span>)
      }
    }
    return vnodes
  }
}
</script>

<style >
span[data-tag]{
  display: inline-block;
  position: relative;
}
span[data-tag]::after {
content: attr(data-tag);
    position: absolute;
    top: 50%;
    right: 0;
    transform: translateX(130%) translateY(-100%);
    background-color: #e64242;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    align-items: center;
    height: 12px;
    line-height: 12px;
    border-radius: 3px;
    padding: 3px;
    font-size: 10px;
    color: white;
}
</style>
