<div class="tabMenuContainer" data-active="tabMenuBBCode-{$tabMenuBBCodeCounter}-0">
	<nav class="tabMenu">
		<ul>
			{foreach from=$tabMenuBBCodeTabs key=tabMenuBBCodeTabKey item=tabMenuBBCodeTab}
				{assign var='tabMenuBBCodeTabID' value='tabMenuBBCode-'|concat:$tabMenuBBCodeCounter:'-':$tabMenuBBCodeTabKey}
				<li><a href="{@$__wcf->getAnchor($tabMenuBBCodeTabID)}" title="{$tabMenuBBCodeTab.title}" rel="nofollow">{$tabMenuBBCodeTab.title}</a></li>
			{/foreach}
		</ul>
	</nav>
	{foreach from=$tabMenuBBCodeTabs key=tabMenuBBCodeTabKey item=tabMenuBBCodeTab}
		{assign var='tabMenuBBCodeTabID' value='tabMenuBBCode-'|concat:$tabMenuBBCodeCounter:'-':$tabMenuBBCodeTabKey}
		<div id="{$tabMenuBBCodeTabID}" class="container containerPadding tabMenuContent{if $tabMenuBBCodeTab[content][0]|isset && $tabMenuBBCodeTab[content][0][title] !== null} tabMenuContainer{/if}">
			{if $tabMenuBBCodeTab[content][0]|isset && $tabMenuBBCodeTab[content][0][title] !== null}
				<nav class="menu">
					<ul>
						{foreach from=$tabMenuBBCodeTab[content] key=tabMenuBBCodeSubTabKey item=tabMenuBBCodeSubTab}
							{assign var='tabMenuBBCodeSubTabID' value='tabMenuBBCode-'|concat:$tabMenuBBCodeCounter:'-':$tabMenuBBCodeTabKey:'-':$tabMenuBBCodeSubTabKey}
							<li><a href="{@$__wcf->getAnchor($tabMenuBBCodeSubTabID)}" title="{$tabMenuBBCodeSubTab.title}" rel="nofollow">{$tabMenuBBCodeSubTab.title}</a></li>
						{/foreach}
					</ul>
				</nav>
			{/if}
			{foreach from=$tabMenuBBCodeTab[content] key=tabMenuBBCodeSubTabKey item=tabMenuBBCodeSubTab}
				{assign var='tabMenuBBCodeSubTabID' value='tabMenuBBCode-'|concat:$tabMenuBBCodeCounter:'-':$tabMenuBBCodeTabKey:'-':$tabMenuBBCodeSubTabKey}
				<div id="{$tabMenuBBCodeSubTabID}" class="hidden">
					{@$tabMenuBBCodeSubTab.content}
				</div>
			{/foreach}
		</div>
	{/foreach}
</div>
<script data-relocate="true">
	//<![CDATA[
		WCF.TabMenu.init();
	//]]>
</script>
