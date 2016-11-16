Object Render Bundle
====================

This bundle enables PHP objects to be rendered in Twig by translating the
object's full name into a Twig template and then rendering that Twig template.

In a CMS it is often necessary to associate a certain (e.g. content) object
with a (view) template by default.

Usage
-----

Imagine that you have a project with various models which you would like to
display.  First it is necessary to configure how the templates should be
located; this is done by mapping partial namespaces to paths.

.. code-block:: yaml

    psi_object_render:
        mapping:
            "Acme\\MyProject\\Model": "models"

This would cause the template for a class such as ``Acme\\MyProject\\Model\\Post`` to be
located at the (Twig) path ``models/Post.html.twig``.

The post can be rendered as follows:

.. code-block:: jinja

    <h1>Posts</h1>
    {{ psi_object_render(post) }}

You then need to create the template - the template will be passed a single
object named ``object``:

.. code-block:: jinja

    {# path/to/templates/models/Post.html.twig #}
    <h2>{{ object.title }}</h2>
    <p>{{ object.body }}</p>

Variants
--------

Variants provide a way to render a variation of the default template.

.. code-block:: jinja

    <h1>Posts</h1>
    {{ psi_object_render(post, 'italicized') }}

Will cause the template at ``path/to/templates/models/Post/italicized.html.twig`` to be rendered.
