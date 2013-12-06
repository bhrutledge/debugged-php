{*
    This template assumes that all user inputs will be in sections with
    headers, and all actions (submit, etc.) will be individual elements.
*}

{if !$form.frozen}
<p>Fields marked with <span class="required">*</span> are required.</p>
{/if}

<form {$form.attributes}>    
    {$form.hidden}

    {foreach item=fieldset key=i from=$form.sections}
        <fieldset class="inputs">
            {if $fieldset.header}
                <legend>{$fieldset.header}</legend>
            {/if}
            <dl>
                {foreach item=element from=$fieldset.elements}
                    {if $element.type neq "group"}
                    <dt>
                        <label for="{$element.name}">{$element.label}</label>
                        {if $element.required}<span class="required">*</span>{/if}
                    </dt>
        
                    <dd>{$element.html}
                        {if $form.errors[$element.name]}
                            <p class="error">{$form.errors[$element.name]}</p>
                        {/if}
                    </dd>
                    
                    {else}
                    <dt>{$element.label}</dt>
                    <dd>
                        {foreach item=group_element from=$element.elements}
                        {$group_element.html}
                        {/foreach}
                    </dd>
                    {/if}
                {/foreach}
            </dl>
        </fieldset>
    {/foreach}
    
    <fieldset class="actions">        
        {if !$form.frozen}
            {foreach item=element key=i from=$form.elements}
                {$element.html}
            {/foreach}
        {/if}
        
        {if $result}
            <p class="{$result.code} message">{$result.message}</p>
        {/if}
        
        {if $form.frozen}
            <p class="reset"><a href="{$id}">reset form</a></p>
        {/if}
    </fieldset>
</form>

        