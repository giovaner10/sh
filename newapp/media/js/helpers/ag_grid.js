import { converterDataFormatada } from "./datas.js";
/**
 * Arquivo criado para ajudar a padronizar a utilização da ag grid, bem como trazer uma serie de helpers,
 * evitando o retrabalho e facilitando o dia a dia do desenvolvedor.  
 */

/**
 * Opções padrões da ag grid
 */
 const AG_GRID_LOCALE_BR = {
  // Set Filter
  selectAll: '(Selecionar tudo)',
  selectAllSearchResults: '(Selecione todos os resultados da pesquisa)',
  searchOoo: 'Procurar...',
  blanks: '(Em branco)',
  noMatches: 'Sem combinações',

  // Number Filter & Text Filter
  filterOoo: 'Filtrando...',
  equals: 'É igual a',
  notEqual: 'Diferente',
  empty: 'Escolha um',

  // Number Filter
  lessThan: 'Menor que',
  greaterThan: 'Maior que',
  lessThanOrEqual: 'Menor ou igual',
  greaterThanOrEqual: 'Maior ou igual',
  inRange: 'Dentro do alcance',
  inRangeStart: 'de',
  inRangeEnd: 'para',

  // Text Filter
  contains: 'Contém',
  notContains: 'Não contém',
  startsWith: 'Começa com',
  endsWith: 'Termina com',

  // Date Filter
  dateFormatOoo: 'yyyy-mm-dd',

  // Filter Conditions
  andCondition: 'E',
  orCondition: 'OU',

  // Filter Buttons
  applyFilter: 'Aplicar',
  resetFilter: 'Resetar',
  clearFilter: 'Limpar',
  cancelFilter: 'Cancelar',

  // Filter Titles
  textFilter: 'Filtro de texto',
  numberFilter: 'Filtro de número',
  dateFilter: 'Filtro de Data',
  setFilter: 'Definir filtro',

  // Side Bar
  columns: 'Colunas',
  filters: 'Filtros',

  // columns tool panel
  pivotMode: 'Pivot Modo',
  groups: 'Grupos de linha',
  rowGroupColumnsEmptyMessage: 'Arraste aqui para definir grupos de linhas',
  values: 'Valores',
  valueColumnsEmptyMessage: 'Arraste aqui para agregar',
  pivots: 'Rótulos de coluna',
  pivotColumnsEmptyMessage: 'Arraste aqui para definir rótulos de coluna',

  // Header of the Default Group Column
  group: 'Grupo',

  // Other
  loadingOoo: 'Carregando...',
  noRowsToShow: 'Sem registros.',
  enabled: 'Habilitado',

  // Menu
  pinColumn: 'Pin coluna',
  pinLeft: 'Pin esquerda',
  pinRight: 'Pin direito',
  noPin: 'Sem pin',
  valueAggregation: 'Agregação de valor',
  autosizeThiscolumn: 'Dimensionar esta coluna automaticamente',
  autosizeAllColumns: 'Tamanho automático de todas as colunas',
  groupBy: 'Grupo por',
  ungroupBy: 'Desagrupar por',
  resetColumns: 'Redefinir colunas',
  expandAll: 'Expandir todos',
  collapseAll: 'Feche tudo',
  copy: 'Copiar',
  ctrlC: 'Ctrl+C',
  copyWithHeaders: 'Copiar com cabeçalhos',
  paste: 'Colar',
  ctrlV: 'Ctrl+V',
  export: 'Exportar',
  csvExport: 'CSV exportar',
  excelExport: 'Excel exportar',

  // Enterprise Menu Aggregation and Status Bar
  sum: 'Soma',
  min: 'Min',
  max: 'Max',
  none: 'Nenhum',
  count: 'Contar',
  avg: 'Média',
  filteredRows: 'Filtrada',
  selectedRows: 'Selecionada',
  totalRows: 'Total de linhas',
  totalAndFilteredRows: 'Linhas',
  more: 'Mais',
  to: 'até',
  of: 'de',
  page: 'Página',
  nextPage: 'Próxima',
  lastPage: 'Última página',
  firstPage: 'Primeira página',
  previousPage: 'Anterior',

  // Pivoting
  pivotColumnGroupTotals: 'Total',

  // Enterprise Menu (Charts)
  pivotChartAndPivotMode: 'Gráfico dinâmico & modo dinâmico',
  pivotChart: 'Gráfico dinâmico',
  chartRange: 'Faixa de gráfico',

  columnChart: 'Coluna',
  groupedColumn: 'Agrupada',
  stackedColumn: 'Empilhada',
  normalizedColumn: '100% empilhada',

  barChart: 'Barra',
  groupedBar: 'Agrupada',
  stackedBar: 'Empilhada',
  normalizedBar: '100% empilhada',

  pieChart: 'Pizza',
  pie: 'Pizza',
  doughnut: 'Rosquinha',

  line: 'Linha',

  xyChart: 'X Y (Espalhar)',
  scatter: 'Espalhar',
  bubble: 'Bolha',

  areaChart: 'Área',
  area: 'Área',
  stackedArea: 'Espalhar',
  normalizedArea: '100% espalhar',

  histogramChart: 'Histograma',

  // Charts
  pivotChartTitle: 'Gráfico dinâmico',
  rangeChartTitle: 'Gráfico de alcance',
  settings: 'Definições',
  data: 'Dados',
  format: 'Formato',
  categories: 'Categorias',
  defaultCategory: '(Nenhum)',
  series: 'Series',
  xyValues: 'X Y valores',
  paired: 'Modo emparelhado',
  axis: 'Eixo',
  navigator: 'Navegador',
  color: 'Cor',
  thickness: 'Espessura',
  xType: 'X modelo',
  automatic: 'Automático',
  category: 'Categoria',
  number: 'Número',
  time: 'Tempo',
  xRotation: 'X rotação',
  yRotation: 'Y rotação',
  ticks: 'Marcação',
  width: 'Largura',
  height: 'Height',
  length: 'Altura',
  padding: 'Preenchimento',
  spacing: 'Espaçamento',
  chart: 'Gráfico',
  title: 'Título',
  titlePlaceholder: 'Título do gráfico - clique duas vezes para editar',
  background: 'Fundo',
  font: 'Fonte',
  top: 'Topo',
  right: 'Direita',
  bottom: 'Fundo',
  left: 'Esquerda',
  labels: 'Etiquetas',
  size: 'Tamanho',
  minSize: 'Tamanho mínimo',
  maxSize: 'Tamanho máximo',
  legend: 'Legenda',
  position: 'Posição',
  markerSize: 'Tamanho do marcador',
  markerStroke: 'Traço do marcador',
  markerPadding: 'Marcador de preenchimento',
  itemSpacing: 'Espaçamento de item',
  itemPaddingX: 'Preenchimento de item X',
  itemPaddingY: 'Preenchimento de item Y',
  layoutHorizontalSpacing: 'Espaçamento horizontal',
  layoutVerticalSpacing: 'Espaçamento vertical',
  strokeWidth: 'Largura do traçado',
  offset: 'Deslocamento',
  offsets: 'Deslocamentos',
  tooltips: 'Dicas de ferramentas',
  callout: 'Chamar',
  markers: 'Marcadores',
  shadow: 'Sombra',
  blur: 'Borrão',
  xOffset: 'X deslocamento',
  yOffset: 'Y deslocamento',
  lineWidth: 'Espessura da linha',
  normal: 'Normal',
  bold: 'Negrito',
  italic: 'itálico',
  boldItalic: 'Negrito e itálico',
  predefined: 'Pré-definido',
  fillOpacity: 'Preencher opacidade',
  strokeOpacity: 'Opacidade da linha',
  histogramBinCount: 'Contagem de bin',
  columnGroup: 'Coluna',
  barGroup: 'Barra',
  pieGroup: 'Pizza',
  lineGroup: 'Linha',
  scatterGroup: 'X Y (Espalhar)',
  areaGroup: 'Área',
  histogramGroup: 'Histograma',
  groupedColumnTooltip: 'Agrupado',
  stackedColumnTooltip: 'Empilhada',
  normalizedColumnTooltip: '100% empilhada',
  groupedBarTooltip: 'Agrupada',
  stackedBarTooltip: 'Empilhada',
  normalizedBarTooltip: '100% empilhada',
  pieTooltip: 'Pizza',
  doughnutTooltip: 'Rosquinha',
  lineTooltip: 'Linha',
  groupedAreaTooltip: 'Área',
  stackedAreaTooltip: 'Empilhada',
  normalizedAreaTooltip: '100% empilhada',
  scatterTooltip: 'Espalhada',
  bubbleTooltip: 'Bolha',
  histogramTooltip: 'Histograma',
  noDataToChart: 'Nenhum dado disponível para mapeamento.',
  pivotChartRequiresPivotMode: 'O gráfico dinâmico requer o modo dinâmico ativado.',
  chartSettingsToolbarTooltip: 'Cardápio',
  chartLinkToolbarTooltip: 'Vinculado à grade',
  chartUnlinkToolbarTooltip: 'Desvinculado da grade',
  chartDownloadToolbarTooltip: 'Baixar gráfico',

  // ARIA
  ariaHidden: 'escondida',
  ariaVisible: 'visível',
  ariaChecked: 'verificada',
  ariaUnchecked: 'não verificado',
  ariaIndeterminate:'indeterminada',
  ariaDefaultListName: 'Lista',
  ariaColumnSelectAll: 'Alternar selecionar todas as colunas',
  ariaInputEditor: 'Editor de entrada',
  ariaDateFilterInput: 'Entrada de filtro de data',
  ariaFilterList: 'Lista de filtros',
  ariaFilterInput: 'Filtro de entrada',
  ariaFilterColumnsInput: 'Entrada de colunas de filtro',
  ariaFilterValue: 'Valor do filtro',
  ariaFilterFromValue: 'Filtro de valor',
  ariaFilterToValue: 'Filtrar por valor',
  ariaFilteringOperator: 'Operador de filtragem',
  ariaColumn: ' ',
  ariaColumnList: 'Lista de colunas',
  ariaColumnGroup: 'Grupo de coluna',
  ariaRowSelect: 'Pressione espaço para selecionar esta linha',
  ariaRowDeselect: 'Pressione espaço para desmarcar esta linha',
  ariaRowToggleSelection: 'Pressione espaço para alternar a seleção de linha',
  ariaRowSelectAll: 'Pressione espaço para alternar a seleção de todas as linhas',
  ariaToggleVisibility: 'Pressione espaço para alternar a visibilidade',
  ariaSearch: 'Procurar',
  ariaSearchFilterValues: 'Valores de filtro de pesquisa',

  // ARIA LABEL FOR DIALOGS
  ariaLabelColumnMenu: 'Menu coluna',
  ariaLabelCellEditor: '',
  ariaLabelDialog: 'Diálogo',
  ariaLabelSelectField: 'Selecione o campo',
  ariaLabelTooltip: 'Dica de ferramenta',
  ariaLabelContextMenu: 'Menu contextual',
  ariaLabelAggregationFunction: 'Função de agregação'

}
const AG_GRID_LOCALE_EN = {
  // Set Filter
  selectAll: '(Select all)',
  selectAllSearchResults: '(Select all search results)',
  searchOoo: 'Search...',
  blanks: '(Blanks)',
  noMatches: 'No matches',

  // Number Filter & Text Filter
  filterOoo: 'Filter...',
  equals: 'Equals',
  notEqual: 'Not equal',
  empty: 'Choose One',

  // Number Filter
  lessThan: 'Less than',
  greaterThan: 'Greater than',
  lessThanOrEqual: 'Less than or equal',
  greaterThanOrEqual: 'Greater than or equal',
  inRange: 'In range',
  inRangeStart: 'from',
  inRangeEnd: 'to',

  // Text Filter
  contains: 'Contains',
  notContains: 'Not contains',
  startsWith: 'Starts with',
  endsWith: 'Ends with',

  // Date Filter
  dateFormatOoo: 'yyyy-mm-dd',

  // Filter Conditions
  andCondition: 'AND',
  orCondition: 'OR',

  // Filter Buttons
  applyFilter: 'Apply',
  resetFilter: 'Reset',
  clearFilter: 'Clear',
  cancelFilter: 'Cancel',

  // Filter Titles
  textFilter: 'Text filter',
  numberFilter: 'Number filter',
  dateFilter: 'Date filter',
  setFilter: 'Set filter',

  // Side Bar
  columns: 'Columns',
  filters: 'Filters',

  // columns tool panel
  pivotMode: 'Pivot mode',
  groups: 'Row groups',
  rowGroupColumnsEmptyMessage: 'Drag here to set row groups',
  values: 'Values',
  valueColumnsEmptyMessage: 'Drag here to aggregate',
  pivots: 'Column labels',
  pivotColumnsEmptyMessage: 'Drag here to set column labels',

  // Header of the Default Group Column
  group: 'Group',

  // Other
  loadingOoo: 'Loading...',
  noRowsToShow: 'No registers.',
  enabled: 'Enabled',

  // Menu
  pinColumn: 'Pin column',
  pinLeft: 'Pin left',
  pinRight: 'Pin right',
  noPin: 'No pin',
  valueAggregation: 'Value aggregation',
  autosizeThiscolumn: 'Autosize this column',
  autosizeAllColumns: 'Autosize all columns',
  groupBy: 'Group by',
  ungroupBy: 'Un-Group by',
  resetColumns: 'Reset columns',
  expandAll: 'Expand all',
  collapseAll: 'Close all',
  copy: 'Copy',
  ctrlC: 'Ctrl+C',
  copyWithHeaders: 'Copy with headers',
  paste: 'Paste',
  ctrlV: 'Ctrl+V',
  export: 'Export',
  csvExport: 'CSV export',
  excelExport: 'Excel export',

  // Enterprise Menu Aggregation and Status Bar
  sum: 'Sum',
  min: 'Min',
  max: 'Max',
  none: 'None',
  count: 'Count',
  avg: 'Average',
  filteredRows: 'Filtered',
  selectedRows: 'Selected',
  totalRows: 'Total rows',
  totalAndFilteredRows: 'Rows',
  more: 'More',
  to: 'to',
  of: 'in',
  page: 'Page',
  nextPage: 'Next page',
  lastPage: 'Last page',
  firstPage: 'First page',
  previousPage: 'Previous page',

  // Pivoting
  pivotColumnGroupTotals: 'Total',

  // Enterprise Menu (Charts)
  pivotChartAndPivotMode: 'Pivot chart & pivot mode',
  pivotChart: 'Pivot chart',
  chartRange: 'Chart range',

  columnChart: 'Column',
  groupedColumn: 'Grouped',
  stackedColumn: 'Stacked',
  normalizedColumn: '100% stacked',

  barChart: 'Bar',
  groupedBar: 'Grouped',
  stackedBar: 'Stacked',
  normalizedBar: '100% stacked',

  pieChart: 'Pie',
  pie: 'Pie',
  doughnut: 'Doughnut',

  line: 'Line',

  xyChart: 'X Y (Scatter)',
  scatter: 'Scatter',
  bubble: 'Bubble',

  areaChart: 'Area',
  area: 'Area',
  stackedArea: 'Stacked',
  normalizedArea: '100% stacked',

  histogramChart: 'Histogram',

  // Charts
  pivotChartTitle: 'Pivot chart',
  rangeChartTitle: 'Range chart',
  settings: 'Settings',
  data: 'Data',
  format: 'Format',
  categories: 'Categories',
  defaultCategory: '(None)',
  series: 'Series',
  xyValues: 'X Y values',
  paired: 'Paired mode',
  axis: 'Axis',
  navigator: 'Navigator',
  color: 'Color',
  thickness: 'Thickness',
  xType: 'X type',
  automatic: 'Automatic',
  category: 'Category',
  number: 'Number',
  time: 'Time',
  xRotation: 'X rotation',
  yRotation: 'Y rotation',
  ticks: 'Ticks',
  width: 'Width',
  height: 'Height',
  length: 'Length',
  padding: 'Padding',
  spacing: 'Spacing',
  chart: 'Chart',
  title: 'Title',
  titlePlaceholder: 'Chart title - double click to edit',
  background: 'Background',
  font: 'Font',
  top: 'Top',
  right: 'Right',
  bottom: 'Bottom',
  left: 'Left',
  labels: 'Labels',
  size: 'Size',
  minSize: 'Minimum size',
  maxSize: 'Maximum size',
  legend: 'Legend',
  position: 'Position',
  markerSize: 'Marker size',
  markerStroke: 'Marker stroke',
  markerPadding: 'Marker padding',
  itemSpacing: 'Item spacing',
  itemPaddingX: 'Item padding X',
  itemPaddingY: 'Item padding Y',
  layoutHorizontalSpacing: 'Horizontal spacing',
  layoutVerticalSpacing: 'Vertical spacing',
  strokeWidth: 'Stroke width',
  offset: 'Offset',
  offsets: 'Offsets',
  tooltips: 'Tooltips',
  callout: 'Callout',
  markers: 'Markers',
  shadow: 'Shadow',
  blur: 'Blur',
  xOffset: 'X offset',
  yOffset: 'Y offset',
  lineWidth: 'Line width',
  normal: 'Normal',
  bold: 'Bold',
  italic: 'Italic',
  boldItalic: 'Bold italic',
  predefined: 'Predefined',
  fillOpacity: 'Fill opacity',
  strokeOpacity: 'Line opacity',
  histogramBinCount: 'Bin count',
  columnGroup: 'Column',
  barGroup: 'Bar',
  pieGroup: 'Pie',
  lineGroup: 'Line',
  scatterGroup: 'X Y (scatter)',
  areaGroup: 'Area',
  histogramGroup: 'Histogram',
  groupedColumnTooltip: 'Grouped',
  stackedColumnTooltip: 'Stacked',
  normalizedColumnTooltip: '100% stacked',
  groupedBarTooltip: 'Grouped',
  stackedBarTooltip: 'Stacked',
  normalizedBarTooltip: '100% stacked',
  pieTooltip: 'Pie',
  doughnutTooltip: 'Doughnut',
  lineTooltip: 'Line',
  groupedAreaTooltip: 'Area',
  stackedAreaTooltip: 'Stacked',
  normalizedAreaTooltip: '100% stacked',
  scatterTooltip: 'Scatter',
  bubbleTooltip: 'Bubble',
  histogramTooltip: 'Histogram',
  noDataToChart: 'No data available to be charted.',
  pivotChartRequiresPivotMode: 'Pivot chart requires pivot mode enabled.',
  chartSettingsToolbarTooltip: 'Menu',
  chartLinkToolbarTooltip: 'Linked to grid',
  chartUnlinkToolbarTooltip: 'Unlinked from grid',
  chartDownloadToolbarTooltip: 'Download chart',

  // ARIA
  ariaHidden: 'hidden',
  ariaVisible: 'visible',
  ariaChecked: 'checked',
  ariaUnchecked: 'unchecked',
  ariaIndeterminate: 'indeterminate',
  ariaDefaultListName: 'List',
  ariaColumnSelectAll: 'Toggle select all columns',
  ariaInputEditor: 'Input editor',
  ariaDateFilterInput: 'Date filter input',
  ariaFilterList: 'Filter list',
  ariaFilterInput: 'Filter input',
  ariaFilterColumnsInput: 'Filter columns input',
  ariaFilterValue: 'Filter value',
  ariaFilterFromValue: 'Filter from value',
  ariaFilterToValue: 'Filter to value',
  ariaFilteringOperator: 'Filtering operator',
  ariaColumn: ' ',
  ariaColumnList: 'Column list',
  ariaColumnGroup: 'Column group',
  ariaRowSelect: 'Press space to select this row',
  ariaRowDeselect: 'Press space to deselect this row',
  ariaRowToggleSelection: 'Press space to toggle row selection',
  ariaRowSelectAll: 'Press space to toggle all rows selection',
  ariaToggleVisibility: 'Press space to toggle visibility',
  ariaSearch: 'Search',
  ariaSearchFilterValues: 'Search filter values',

  // ARIA LABEL FOR DIALOGS
  ariaLabelColumnMenu: 'Column menu',
  ariaLabelCellEditor: '',
  ariaLabelDialog: 'Dialog',
  ariaLabelSelectField: 'Select field',
  ariaLabelTooltip: 'Tooltip',
  ariaLabelContextMenu: 'Context menu',
  ariaLabelSubMenu: 'SubMenu',
  ariaLabelAggregationFunction: 'Aggregation function',
}
const AG_GRID_LOCALE_ES = {
  // Set Filter
  selectAll: '(Seleccionar todo)',
  selectAllSearchResults: '(Seleccionar todos los resultados de la búsqueda)',
  searchOoo: 'Buscar...',
  blanks: '(En blanco)',
  noMatches: 'No hay coincidencias',

  // Number Filter & Text Filter
  filterOoo: 'Filtración...',
  equals: 'Es igual a',
  notEqual: 'Diferente',
  empty: 'Escoge uno',

  // Number Filter
  lessThan: 'Menos que',
  greaterThan: 'Más grande que',
  lessThanOrEqual: 'Menor o igual',
  greaterThanOrEqual: 'Más grande o igual',
  inRange: 'Al alcance',
  inRangeStart: 'en',
  inRangeEnd: 'por',

  // Text Filter
  contains: 'Contiene',
  notContains: 'No contiene',
  startsWith: 'Empieza con',
  endsWith: 'termina con',

  // Date Filter
  dateFormatOoo: 'yyyy-mm-dd',

  // Filter Conditions
  andCondition: 'Y',
  orCondition: 'O',

  // Filter Buttons
  applyFilter: 'Aplicar',
  resetFilter: 'Reiniciar',
  clearFilter: 'Limpiar',
  cancelFilter: 'Cancelar',

  // Filter Titles
  textFilter: 'Filtro de texto',
  numberFilter: 'Filtro de número',
  dateFilter: 'Filtro de fecha',
  setFilter: 'Definir filtro',

  // Side Bar
  columns: 'Columnas',
  filters: 'Filtros',

  // columns tool panel
  pivotMode: 'Pivot Modo',
  groups: 'Grupos de línea',
  rowGroupColumnsEmptyMessage: 'Arrastre aquí para definir grupos de líneas',
  values: 'Valores',
  valueColumnsEmptyMessage: 'Arrastre aquí para agregar',
  pivots: 'Etiquetas de columna',
  pivotColumnsEmptyMessage: 'Arrastre aquí para definir etiquetas de columna',

  // Header of the Default Group Column
  group: 'Grupo',

  // Other
  loadingOoo: 'Cargando...',
  noRowsToShow: 'No hay registros.',
  enabled: 'Capaz',

  // Menu
  pinColumn: 'Pin columna',
  pinLeft: 'Pin izquierda',
  pinRight: 'Pin derecha',
  noPin: 'Sin pin',
  valueAggregation: 'Valor agregado',
  autosizeThiscolumn: 'Tamaño de esta columna automáticamente',
  autosizeAllColumns: 'Tamaño automático de todas las columnas',
  groupBy: 'Agrupar por',
  ungroupBy: 'Desagrupar por',
  resetColumns: 'Restablecer columnas',
  expandAll: 'Expandir todo',
  collapseAll: 'Cierra todo',
  copy: 'Dupdo',
  ctrlC: 'Ctrl+C',
  copyWithHeaders: 'Copiar con encabezados',
  paste: 'Pegar',
  ctrlV: 'Ctrl+V',
  export: 'Exportar',
  csvExport: 'CSV exportar',
  excelExport: 'Excel exportar',

  // Enterprise Menu Aggregation and Status Bar
  sum: 'Suma',
  min: 'Min',
  max: 'Max',
  none: 'Ninguno',
  count: 'Contar',
  avg: 'Promedio',
  filteredRows: 'Filtrado',
  selectedRows: 'Seleccionado',
  totalRows: 'Líneas totales',
  totalAndFilteredRows: 'Líneas',
  more: 'La mayoría',
  to: 'hasta',
  of: 'en',
  page: 'Página',
  nextPage: 'Siguiente',
  lastPage: 'Última página',
  firstPage: 'Primera pagina',
  previousPage: 'Anterior',

  // Pivoting
  pivotColumnGroupTotals: 'Total',

  // Enterprise Menu (Charts)
  pivotChartAndPivotMode: 'Gráfico dinámico & modo dinámico',
  pivotChart: 'Gráfico dinámico',
  chartRange: 'Rango de gráfico',

  columnChart: 'Columna',
  groupedColumn: 'Agrupado',
  stackedColumn: 'Apilado',
  normalizedColumn: '100% apilado',

  barChart: 'Bar',
  groupedBar: 'Agrupado',
  stackedBar: 'Apilado',
  normalizedBar: '100% apilado',

  pieChart: 'Pizza',
  pie: 'Pizza',
  doughnut: 'Rosquilla',

  line: 'Línea',

  xyChart: 'X Y (Para difundir)',
  scatter: 'Para difundir',
  bubble: 'Burbuja',

  areaChart: 'Zona',
  area: 'Zona',
  stackedArea: 'Para difundir',
  normalizedArea: '100% para difundir',

  histogramChart: 'Histograma',

  // Charts
  pivotChartTitle: 'Gráfico dinámico',
  rangeChartTitle: 'Gráfico de rango',
  settings: 'Definiciones',
  data: 'Datos',
  format: 'Formato',
  categories: 'Categorías',
  defaultCategory: '(Ninguno)',
  series: 'Serie',
  xyValues: 'X Y valores',
  paired: 'Modo emparejado',
  axis: 'Eje',
  navigator: 'Navegador',
  color: 'Color',
  thickness: 'Espesor',
  xType: 'X modelo',
  automatic: 'Automático',
  category: 'Categoría',
  number: 'Número',
  time: 'Tiempo',
  xRotation: 'X rotación',
  yRotation: 'Y rotación',
  ticks: 'Calificación',
  width: 'Ancho',
  height: 'Altura',
  length: 'La cantidad',
  padding: 'Llenar',
  spacing: 'Espaciado',
  chart: 'Gráfico',
  title: 'Título',
  titlePlaceholder: 'Título del gráfico: haga doble clic para editar',
  background: 'Fondo',
  font: 'Fuente',
  top: 'Cima',
  right: 'Derecha',
  bottom: 'Fondo',
  left: 'Izquierda',
  labels: 'Etiquetas colgantes',
  size: 'Tamaño',
  minSize: 'Talla minima',
  maxSize: 'Talla máxima',
  legend: 'Subtitular',
  position: 'Posición',
  markerSize: 'Tamaño del marcador',
  markerStroke: 'Rastro de marcador',
  markerPadding: 'Marcador de relleno',
  itemSpacing: 'Espaciado de elementos',
  itemPaddingX: 'Ilenado de artículos X',
  itemPaddingY: 'Ilenado de artículos Y',
  layoutHorizontalSpacing: 'Espaciado horizontal',
  layoutVerticalSpacing: 'Espaciado vertical',
  strokeWidth: 'Anchura del trazo',
  offset: 'Desplazamiento',
  offsets: 'Desplazamientos',
  tooltips: 'Consejos sobre herramientas',
  callout: 'Llamar',
  markers: 'Marcadores',
  shadow: 'Sombra',
  blur: 'Difuminar',
  xOffset: 'X desplazamiento',
  yOffset: 'Y desplazamiento',
  lineWidth: 'Ancho de línea',
  normal: 'Normal',
  bold: 'Negrita',
  italic: 'Itálico',
  boldItalic: 'Negrita e itálico',
  predefined: 'Predefinido',
  fillOpacity: 'Relleno de opacidad',
  strokeOpacity: 'Opacidad de la línea',
  histogramBinCount: 'Recuento de contenedores',
  columnGroup: 'Columna',
  barGroup: 'Bar',
  pieGroup: 'Pizza',
  lineGroup: 'Línea',
  scatterGroup: 'X Y (Para difundir)',
  areaGroup: 'Zona',
  histogramGroup: 'Histograma',
  groupedColumnTooltip: 'Agrupado',
  stackedColumnTooltip: 'Apilado',
  normalizedColumnTooltip: '100% apilado',
  groupedBarTooltip: 'Agrupada',
  stackedBarTooltip: 'Apilado',
  normalizedBarTooltip: '100% apilado',
  pieTooltip: 'Pizza',
  doughnutTooltip: 'Rosquilla',
  lineTooltip: 'Línea',
  groupedAreaTooltip: 'Zona',
  stackedAreaTooltip: 'Apilado',
  normalizedAreaTooltip: '100% apilado',
  scatterTooltip: 'Extendido',
  bubbleTooltip: 'Burbuja',
  histogramTooltip: 'Histograma',
  noDataToChart: 'No hay datos disponibles para mapear.',
  pivotChartRequiresPivotMode: 'El gráfico dinámico requiere el modo dinámico habilitado.',
  chartSettingsToolbarTooltip: 'Menú',
  chartLinkToolbarTooltip: 'Vinculado a la cuadrícula',
  chartUnlinkToolbarTooltip: 'Separado de la rejilla',
  chartDownloadToolbarTooltip: 'Descargar gráfico',

  // ARIA
  ariaHidden: 'oculto',
  ariaVisible: 'visible',
  ariaChecked: 'verificado',
  ariaUnchecked: 'no verificado',
  ariaIndeterminate:'indeterminado',
  ariaDefaultListName: 'Lista',
  ariaColumnSelectAll: 'Alternar seleccionar todas las columnas',
  ariaInputEditor: 'Editor de entrada',
  ariaDateFilterInput: 'Entrada de filtro de fecha',
  ariaFilterList: 'Lista de filtros',
  ariaFilterInput: 'Filtro de entrada',
  ariaFilterColumnsInput: 'Entrada de columnas de filtro',
  ariaFilterValue: 'Valor de filtro',
  ariaFilterFromValue: 'Filtro de valor',
  ariaFilterToValue: 'Filtrar por valor',
  ariaFilteringOperator: 'Operador de filtro',
  ariaColumn: ' ',
  ariaColumnList: 'Lista de columnas',
  ariaColumnGroup: 'Grupo de columnas',
  ariaRowSelect: 'Presione espacio para seleccionar esta línea',
  ariaRowDeselect: 'Presiona espacio para anular la selección de esta línea',
  ariaRowToggleSelection: 'Presione la barra espaciadora para alternar la selección de línea',
  ariaRowSelectAll: 'Presione la barra espaciadora para alternar la selección de todas las líneas',
  ariaToggleVisibility: 'Presione espacio para alternar la visibilidad',
  ariaSearch: 'Buscar',
  ariaSearchFilterValues: 'Valores de filtro de búsqueda',

  // ARIA LABEL FOR DIALOGS
  ariaLabelColumnMenu: 'Menú de columna',
  ariaLabelCellEditor: '',
  ariaLabelDialog: 'Diálogo',
  ariaLabelSelectField: 'Seleccionar campo',
  ariaLabelTooltip: 'Punta de herramienta',
  ariaLabelContextMenu: 'Menú contextual',
  ariaLabelSubMenu: 'SubMenú',
  ariaLabelAggregationFunction: 'Función de agregación'

}
export const AG_GRID_DEFAULT_COL_DEF = {
  editable: false,
  resizable: true,
  suppressMenu: true,
  sortable: true,
  suppressMovable: true,
}
export const AG_GRID_DEFAULT_SIDEBAR = {
  toolPanels: [
    {
      id: "columns",
      labelDefault: lang['colunas'],
      iconKey: "columns",
      toolPanel: "agColumnsToolPanel",
      toolPanelParams: {
        suppressRowGroups: true,
        suppressValues: true,
        suppressPivots: true,
        suppressPivotMode: true,
        suppressColumnFilter: false,
        suppressColumnSelectAll: false,
        suppressColumnExpandAll: true,
      },
    },
  ],
  defaultToolPanel: false,
}
export const AG_GRID_DEFAULT_ROW_HEIGHT = 40;

/**
 * Retorna as opções padrões da ag grid, permitindo personalização das mesmas.
 * @param {string} idioma 'pt'|'en'|'es' 
 * @param {*} opcoes opcoes da ag grid https://www.ag-grid.com/javascript-data-grid/grid-options/
 */
export function pegarOpcoesPadroesAgGrid(opcoes = {}, idioma = idiomaUsuario) {
  const padrao = {
    localeText: pegarTraducaoAgGrid(idioma),
    rowHeight: AG_GRID_DEFAULT_ROW_HEIGHT,
    defaultColDef: AG_GRID_DEFAULT_COL_DEF,
    overlayLoadingTemplate: montarOverlayLoadingTemplatePadrao(),
    animateRows: "true",
    rowData: null,
    pagination: true,
    paginationPageSize: 25,
    sideBar: AG_GRID_DEFAULT_SIDEBAR,
  }

  return { ...padrao, ...opcoes };
}

/**
 * Retorna a tradução da ag grid de acordo com o idioma.
 * @param {string} idioma 'pt'|'en'|'es' 
 */
export function pegarTraducaoAgGrid(idioma = idiomaUsuario) {
  const traducoes = {
    pt:  AG_GRID_LOCALE_BR,
    en:  AG_GRID_LOCALE_EN,
    es:  AG_GRID_LOCALE_ES,
  }

  return traducoes[idioma] || AG_GRID_LOCALE_BR;
}

/**
 * Retorna o template do overlay loading formato padrão.
 * @param {string} htmlText 
 */
export function montarOverlayLoadingTemplatePadrao(htmlText = `${lang["carregando"]}...`) {
  return `
    <span class="form-group ag-overlay-loading-center" style="border-radius: 12px; border:none; padding: 10px;">
      <div>
          ${htmlText}
      </div>
    </span>
  `;
}

/**
 * Data formatadas não são ordenadas corretamente, através da opção
 * comparator esta função as datas corretamente
 * https://www.ag-grid.com/javascript-data-grid/row-sorting/#custom-sorting
 * 
 * @param {string} data1 
 * @param {string} data2 
 * @param {string} idioma 'pt'|'en'|'es' 
 * @returns 
 */
export function compararDatasAgGrid(data1, data2, idioma = idiomaUsuario) {
  const msData1 = !data1 ? null : converterDataFormatada(data1, idioma, false)?.getTime();
  const msData2 = !data2 ? null : converterDataFormatada(data2, idioma, false)?.getTime();

  if (msData1 === null && msData2 === null) return 0;
  if (msData1 === null || data1 === "00:00:00") return -1;
  if (msData2 === null || data2 === "00:00:00") return 1;

  return msData1 > msData2 ? 1 : -1;
}

/**
 * Muda o tamanho de registros por página da ag grid
 * @param {object} gridOptions opcoes da ag grid https://www.ag-grid.com/javascript-data-grid/grid-options/
 * @param {string} idPageSize id do select do tamanho da página da ag grid
 */
 export function tamanhoPaginaAlterado(gridOptions, idPageSize) {
  const value = document.getElementById(idPageSize).value;
  gridOptions.api.paginationSetPageSize(Number(value));
}

/**
 * Útil quando se deseja pegar os dados da linha pelo rowIndex e não necessáriamente pelo id.
 * @param { number } rowIndex 
 * @returns { any|undefined } rowNode
 */
export function obterDadosDaLinhaPeloRowIndex(gridOptions, rowIndex) {
  let dados = undefined;
  gridOptions.api.forEachNode((rowNode) => {
    if (rowNode.rowIndex === rowIndex) {
      dados = rowNode;
    }
  });

  return dados;
}
